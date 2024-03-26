<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\CMS\Core\Database\Query\Restriction;

use TYPO3\CMS\Core\Database\Query\Expression\CompositeExpression;
use TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Base class for query restriction collections
 */
abstract class AbstractRestrictionContainer implements QueryRestrictionContainerInterface
{
    /**
     * @var QueryRestrictionInterface[]
     */
    protected $restrictions = [];

    /**
     * @var QueryRestrictionInterface[]
     */
    protected $enforcedRestrictions = [];

    /**
     * Main method to build expressions for given tables.
     * Iterating over all registered expressions and combine them with AND
     *
     * @param array $queriedTables Array of tables, where array key is table alias and value is a table name
     * @param ExpressionBuilder $expressionBuilder Expression builder instance to add restrictions with
     * @return CompositeExpression The result of query builder expression(s)
     */
    public function buildExpression(array $queriedTables, ExpressionBuilder $expressionBuilder): CompositeExpression
    {
        $constraints = [];
        foreach ($this->restrictions as $restriction) {
            $constraints[] = $restriction->buildExpression($queriedTables, $expressionBuilder);
        }
        return $expressionBuilder->and(...$constraints);
    }

    /**
     * Removes all restrictions stored within this container
     */
    public function removeAll(): QueryRestrictionContainerInterface
    {
        $this->restrictions = $this->enforcedRestrictions;
        return $this;
    }

    /**
     * Remove restriction of a given type
     *
     * @param string $restrictionType Class name of the restriction to be removed
     */
    public function removeByType(string $restrictionType): QueryRestrictionContainerInterface
    {
        unset($this->restrictions[$restrictionType], $this->enforcedRestrictions[$restrictionType]);
        return $this;
    }

    /**
     * Add a new restriction instance to this collection
     */
    public function add(QueryRestrictionInterface $restriction): QueryRestrictionContainerInterface
    {
        $this->restrictions[get_class($restriction)] = $restriction;
        if ($restriction instanceof EnforceableQueryRestrictionInterface && $restriction->isEnforced()) {
            $this->enforcedRestrictions[get_class($restriction)] = $restriction;
        }
        return $this;
    }

    /**
     * Factory method for restrictions.
     * Currently only instantiates the class.
     *
     * @param string $restrictionClass
     */
    protected function createRestriction($restrictionClass): QueryRestrictionInterface
    {
        return GeneralUtility::makeInstance($restrictionClass);
    }
}
