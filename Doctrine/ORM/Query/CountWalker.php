<?php

/*
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
use Doctrine\ORM\Query\AST\SelectExpression;
use Doctrine\ORM\Query\AST\PathExpression;
use Doctrine\ORM\Query\AST\AggregateExpression;

/**
 * Count Query TreeWalker for Countable functionality in doctrine paginator.
 * Replaces the selectClause of the AST with a COUNT statement.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class CountWalker extends TreeWalkerAdapter
{
    /**
     * Distinct mode hint name.
     */
    const HINT_DISTINCT = 'sonatra_paginator.distinct';

    /**
     * Walks down a SelectStatement AST node, modifying it to retrieve a COUNT.
     *
     * @param SelectStatement $AST
     */
    public function walkSelectStatement(SelectStatement $AST)
    {
        $rootComponents = array();

        foreach ($this->_getQueryComponents() as $dqlAlias => $qComp) {
            $isParent = array_key_exists('parent', $qComp)
                && $qComp['parent'] === null
                && $qComp['nestingLevel'] == 0
            ;

            if ($isParent) {
                $rootComponents[] = array($dqlAlias => $qComp);
            }
        }

        if (count($rootComponents) > 1) {
            throw new \RuntimeException("Cannot count query which selects two FROM components, cannot make distinction");
        }

        $root = reset($rootComponents);
        $parentName = key($root);
        $parent = current($root);

        $pathExpression = new PathExpression(
                PathExpression::TYPE_STATE_FIELD | PathExpression::TYPE_SINGLE_VALUED_ASSOCIATION, $parentName,
                $parent['metadata']->getSingleIdentifierFieldName()
        );
        $pathExpression->type = PathExpression::TYPE_STATE_FIELD;

        $distinct = $this->_getQuery()->getHint(self::HINT_DISTINCT);
        $AST->selectClause->selectExpressions = array(
                new SelectExpression(
                        new AggregateExpression('count', $pathExpression, $distinct), null
                )
        );

        // ORDER BY is not needed
        $AST->orderByClause = null;
    }
}
