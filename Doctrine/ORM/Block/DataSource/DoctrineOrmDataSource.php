<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Doctrine\ORM\Block\DataSource;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Sonatra\Bundle\BootstrapBundle\Block\DataSource\DataSource;
use Sonatra\Bundle\BootstrapBundle\Doctrine\ORM\Query\OrderByWalker;
use Sonatra\Bundle\BlockBundle\Block\Exception\BadMethodCallException;
use Sonatra\Bundle\BlockBundle\Block\Exception\InvalidArgumentException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class DoctrineOrmDataSource extends DataSource
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * @var bool
     */
    protected $hasTranslatable;

    /**
     * Constructor.
     *
     * @param EntityManager             $entityManager    The entity manager
     * @param string                    $rowId            The data fieldname for unique id row definition
     * @param PropertyAccessorInterface $propertyAccessor The property accessor
     */
    public function __construct(EntityManager $entityManager, $rowId = null, PropertyAccessorInterface $propertyAccessor = null)
    {
        parent::__construct($rowId, $propertyAccessor);

        $this->em = $entityManager;
        $this->hasTranslatable = false;

        if ($this->em->getEventManager()->hasListeners('postLoad')) {
            foreach ($this->em->getEventManager()->getListeners('postLoad') as $listener) {
                if ('Gedmo\Translatable\TranslatableListener' === get_class($listener)) {
                    $this->hasTranslatable = true;
                    break;
                }
            }
        }
    }

    /**
     * Set query.
     *
     * @param Query $query
     *
     * @return DoctrineOrmDataSource
     */
    public function setQuery($query)
    {
        $this->cacheRows = null;
        $this->size = null;
        $this->paginator = new Paginator($query);

        return $this;
    }

    /**
     * Get query.
     *
     * @return Query
     */
    public function getQuery()
    {
        return null !== $this->paginator ? $this->paginator->getQuery() : null;
    }

    /**
     * {@inheritdoc}
     */
    public function setRows($rows)
    {
        throw new BadMethodCallException('The "setRows" method is not available. Uses "setQuery" method');
    }

    /**
     * {@inheritdoc}
     */
    public function getRows()
    {
        if (null !== $this->cacheRows) {
            return $this->cacheRows;
        }

        if (null === $this->paginator) {
            throw new BadMethodCallException('The query must be informed before the "getRows" method');
        }

        $this->cacheRows = array();
        $sortColumns = $this->getSortColumns();
        $query = $this->paginator->getQuery();

        $query
            ->setFirstResult($this->getStart() - 1)
            ->setMaxResults($this->getPageSize());

        // query options
        $tkc = 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker';

        if ($this->hasTranslatable && class_exists($tkc)) {
            $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, $tkc);
            $query->setHint(\Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE, $this->getLocale());
        }

        // query sort
        if (count($sortColumns) > 0) {
            $customTreeWalkers = $query->getHint(Query::HINT_CUSTOM_TREE_WALKERS);

            if ($customTreeWalkers !== false && is_array($customTreeWalkers)) {
                $customTreeWalkers = array_merge($customTreeWalkers, array(OrderByWalker::class));
            } else {
                $customTreeWalkers = array(OrderByWalker::class);
            }

            $query->setHint(Query::HINT_CUSTOM_TREE_WALKERS, $customTreeWalkers);

            $aliases = array();
            $fieldNames = array();
            $sorts = array();

            foreach ($sortColumns as $sortConfig) {
                if (!isset($sortConfig['name'])) {
                    throw new InvalidArgumentException("The 'name' property of sort_columns option must be present");
                }

                $field = $sortConfig['name'];
                $index = $this->getColumnIndex($field);
                $sort = isset($sortConfig['sort']) ? $sortConfig['sort'] : 'asc';

                $exp = explode('.', $index);

                if (1 === count($exp)) {
                    array_unshift($exp, false);
                }

                $aliases[] = $exp[0];
                $fieldNames[] = $exp[1];
                $sorts[] = $sort;
            }

            $query->setHint(OrderByWalker::HINT_SORT_ALIAS, $aliases);
            $query->setHint(OrderByWalker::HINT_SORT_FIELD, $fieldNames);
            $query->setHint(OrderByWalker::HINT_SORT_DIRECTION, $sorts);
        }

        $this->cacheRows = $this->paginateRows($this->paginator->getIterator()->getArrayCopy(), $this->getStart());

        return $this->cacheRows;
    }

    /**
     * {@inheritdoc}
     */
    protected function calculateSize()
    {
        return (integer) null !== $this->paginator ? $this->paginator->count() : 0;
    }
}
