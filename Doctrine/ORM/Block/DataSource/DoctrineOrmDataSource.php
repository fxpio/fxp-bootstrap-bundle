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

use Sonatra\Bundle\BootstrapBundle\Block\DataSource\DataSource;
use Sonatra\Bundle\BootstrapBundle\Doctrine\ORM\Query\CountWalker;
use Sonatra\Bundle\BootstrapBundle\Doctrine\ORM\Query\OrderByWalker;
use Sonatra\Bundle\BlockBundle\Block\Exception\BadMethodCallException;
use Sonatra\Bundle\BlockBundle\Block\Exception\InvalidArgumentException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

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
     * @var Query
     */
    protected $query;

    /**
     * @var bool
     */
    protected $hasTranslatable;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager
     * @param string        $rowId         The data fieldname for unique id row definition
     */
    public function __construct(EntityManager $entityManager, $rowId = null)
    {
        parent::__construct($rowId);

        $this->em = $entityManager;
        $this->hasTranslatable = false;

        foreach ($this->em->getEventManager()->getListeners('postLoad') as $listener) {
            if ('Gedmo\Translatable\TranslatableListener' === get_class($listener)) {
                $this->hasTranslatable = true;
                break;
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
        $this->query = $query;

        return $this;
    }

    /**
     * Get query.
     *
     * @return Query
     */
    public function getQuery()
    {
        return $this->query;
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

        if (null === $this->query) {
            throw new BadMethodCallException('The query must be informed before the "getRows" method');
        }

        $this->cacheRows = array();

        $query = clone $this->query;
        $lengthItems = $this->getSize();
        $sortColumns = $this->getSortColumns();

        // query options
        $tkc = 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker';

        if ($this->hasTranslatable && class_exists($tkc)) {
            $query->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, $tkc);
            $query->setHint(\Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE, $this->getLocale());
        }

        // query sort
        if (count($sortColumns) > 0) {
            $walker = 'Sonatra\Bundle\\BootstrapBundle\\Doctrine\\ORM\\Query\\OrderByWalker';
            $customTreeWalkers = $query->getHint(Query::HINT_CUSTOM_TREE_WALKERS);

            if ($customTreeWalkers !== false && is_array($customTreeWalkers)) {
                $customTreeWalkers = array_merge($customTreeWalkers, array($walker));

            } else {
                $customTreeWalkers = array($walker);
            }

            $query->setHint(Query::HINT_CUSTOM_TREE_WALKERS, $customTreeWalkers);

            $aliases = array();
            $fieldNames = array();
            $sorts = array();

            for ($i=0; $i<count($sortColumns); $i++) {
                $field = array_keys($sortColumns[$i])[0];
                $index = $this->getColumnIndex($field);
                $sort = $sortColumns[$i][$field];

                $exp = explode('.', $index);

                if (0 === count($exp)) {
                    throw new InvalidArgumentException("The index '$index' must have a alias");
                }

                $aliases[] = $exp[0];
                $fieldNames[] = $exp[1];
                $sorts[] = $sort;
            }

            $query->setHint(OrderByWalker::HINT_SORT_ALIAS, $aliases);
            $query->setHint(OrderByWalker::HINT_SORT_FIELD, $fieldNames);
            $query->setHint(OrderByWalker::HINT_SORT_DIRECTION, $sorts);
        }

        // paginate init
        $query
            ->setFirstResult($this->getStart() - 1)
            ->setMaxResults($this->getPageSize());

        $this->cacheRows = $this->paginateRows($query->getResult(), $this->getStart());

        return $this->cacheRows;
    }

    /**
     * {@inheritdoc}
     */
    protected function calculateSize()
    {
        $cQuery = clone $this->query;
        $cQuery->setParameters($this->query->getParameters());
        $cQuery
            ->setFirstResult(null)
            ->setMaxResults(null)
            ->setHint(Query::HINT_CUSTOM_TREE_WALKERS, array('Sonatra\Bundle\\BootstrapBundle\\Doctrine\\ORM\\Query\\CountWalker'));

        return (integer) $cQuery->getSingleScalarResult();
    }
}
