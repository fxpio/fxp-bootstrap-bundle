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
     * Constructor.
     *
     * @param EntityManager $entityManager
     * @param string        $rowId         The data fieldname for unique id row definition
     */
    public function __construct(EntityManager $entityManager, $rowId = null)
    {
        parent::__construct($rowId);

        $this->em = $entityManager;
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
        $this->setStart(($this->getPageNumber() - 1) * $this->getPageSize() + 1);

        $query = clone $this->query;
        $pageSize = $this->getPageSize();
        $pageNumber = $this->getPageNumber();
        $lengthItems = $this->getSize();
        $sortColumns = $this->getSortColumns();
        $rowNumber = $this->getStart();

        // query options
        $tkc = 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker';

        if (class_exists($tkc)) {
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

        // paginate count
        $cQuery = clone $query;
        $cQuery->setParameters($query->getParameters());
        $cQuery
            ->setFirstResult(null)
            ->setMaxResults(null)
            ->setHint(Query::HINT_CUSTOM_TREE_WALKERS, array('Sonatra\Bundle\\BootstrapBundle\\Doctrine\\ORM\\Query\\CountWalker'));
        $totalItems = (integer) $cQuery->getSingleScalarResult();
        $totalPages = 0;

        if ($pageSize > 0 && $totalItems > 0) {
            $totalPages = (integer) ceil($totalItems / $pageSize);

            // paginate init
            $query
                ->setFirstResult(($pageNumber - 1) * $pageSize)
                ->setMaxResults($pageSize);
        }

        // save config
        $this->setSize($totalItems);
        $this->setPageSize($pageSize);
        $this->setPageNumber($pageNumber);
        $this->setPageCount($totalPages);

        $pagination = $query->getResult();
        $this->cacheRows = $this->paginateRows($pagination, $rowNumber);

        return $this->cacheRows;
    }
}
