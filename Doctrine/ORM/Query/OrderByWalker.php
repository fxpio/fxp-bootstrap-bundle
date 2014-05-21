<?php

/**
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BootstrapBundle\Doctrine\ORM\Query;

use Doctrine\ORM\Query\TreeWalkerAdapter;
use Doctrine\ORM\Query\AST\SelectStatement;
use Doctrine\ORM\Query\AST\PathExpression;
use Doctrine\ORM\Query\AST\OrderByItem;
use Doctrine\ORM\Query\AST\OrderByClause;

/**
 * OrderBy Query TreeWalker for Sortable functionality
 * in doctrine paginator.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class OrderByWalker extends TreeWalkerAdapter
{
    /**
     * Sort key alias hint name.
     */
    const HINT_SORT_ALIAS = 'sonatra_paginator.sort.alias';

    /**
     * Sort key field hint name.
     */
    const HINT_SORT_FIELD = 'sonatra_paginator.sort.field';

    /**
     * Sort direction hint name.
     */
    const HINT_SORT_DIRECTION = 'sonatra_paginator.sort.direction';

    /**
     * {@inheritdoc}
     */
    public function walkSelectStatement(SelectStatement $AST)
    {
        $query = $this->_getQuery();

        // execute a walker for hint with string value
        if (!is_array($query->getHint(self::HINT_SORT_FIELD))) {
            parent::walkSelectStatement($AST);

            return;
        }

        // execute a walker for hint with array value
        $fields = $query->getHint(self::HINT_SORT_FIELD);
        $aliases = $query->getHint(self::HINT_SORT_ALIAS);
        $directions = $query->getHint(self::HINT_SORT_DIRECTION);
        $components = $this->_getQueryComponents();

        // init ordering
        $AST->orderByClause = new OrderByClause(array());

        if (!is_array($aliases) || !is_array($directions)) {
            throw new \InvalidArgumentException("The HINT_SORT_ALIAS ans HINT_SORT_DIRECTION must be an array");
        }

        for ($i=0; $i<count($fields); $i++) {
            $field = $fields[$i];
            $alias = $aliases[$i];
            $direction = $directions[$i];

            if ($alias !== false) {
                if (!array_key_exists($alias, $components)) {
                    throw new \UnexpectedValueException("There is no component aliased by [{$alias}] in the given Query");
                }

                $meta = $components[$alias];

                if (!$meta['metadata']->hasField($field)) {
                    throw new \UnexpectedValueException("There is no such field [{$field}] in the given Query component, aliased by [$alias]");
                }

            } else {
                if (!array_key_exists($field, $components)) {
                    throw new \UnexpectedValueException("There is no component field [{$field}] in the given Query");
                }
            }

            if ($alias !== false) {
                $pathExpression = new PathExpression(PathExpression::TYPE_STATE_FIELD, $alias, $field);
                $pathExpression->type = PathExpression::TYPE_STATE_FIELD;

            } else {
                $pathExpression = $field;
            }

            $orderByItem = new OrderByItem($pathExpression);
            $orderByItem->type = $direction;

            $AST->orderByClause->orderByItems[] = $orderByItem;
        }
    }
}
