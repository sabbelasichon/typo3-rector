<?php

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

namespace TYPO3\CMS\Extbase\Persistence;

use TYPO3\CMS\Extbase\Persistence\Generic\Qom\AndInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ComparisonInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\OrInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\SourceInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;

/**
 * A persistence query interface
 * @template T of object
 */
interface QueryInterface
{
    /**
     * The '=' comparison operator.
     */
    public const OPERATOR_EQUAL_TO = 1;

    /**
     * For NULL we have to use 'IS' instead of '='
     */
    public const OPERATOR_EQUAL_TO_NULL = 101;

    /**
     * The '!=' comparison operator.
     */
    public const OPERATOR_NOT_EQUAL_TO = 2;

    /**
     * For NULL we have to use 'IS NOT' instead of '!='
     */
    public const OPERATOR_NOT_EQUAL_TO_NULL = 202;

    /**
     * The '<' comparison operator.
     */
    public const OPERATOR_LESS_THAN = 3;

    /**
     * The '<=' comparison operator.
     */
    public const OPERATOR_LESS_THAN_OR_EQUAL_TO = 4;

    /**
     * The '>' comparison operator.
     */
    public const OPERATOR_GREATER_THAN = 5;

    /**
     * The '>=' comparison operator.
     */
    public const OPERATOR_GREATER_THAN_OR_EQUAL_TO = 6;

    /**
     * The 'like' comparison operator.
     */
    public const OPERATOR_LIKE = 7;

    /**
     * The 'contains' comparison operator for collections.
     */
    public const OPERATOR_CONTAINS = 8;

    /**
     * The 'in' comparison operator.
     */
    public const OPERATOR_IN = 9;

    /**
     * The 'is NULL' comparison operator.
     */
    public const OPERATOR_IS_NULL = 10;

    /**
     * The 'is empty' comparison operator for collections.
     */
    public const OPERATOR_IS_EMPTY = 11;

    /**
     * Constants representing the direction when ordering result sets.
     */
    public const ORDER_ASCENDING = 'ASC';
    public const ORDER_DESCENDING = 'DESC';

    /**
     * Gets the node-tuple source for this query.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Qom\SourceInterface the node-tuple source; non-NULL
     */
    public function getSource();

    /**
     * Executes the query and returns the result.
     *
     * @param bool $returnRawQueryResult avoids the object mapping by the persistence
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|object[] The query result object or an array if $returnRawQueryResult is TRUE
     * @phpstan-return ($returnRawQueryResult is true ? list<T> : QueryResultInterface<int,T>)
     */
    public function execute($returnRawQueryResult = false);

    /**
     * Sets the property names to order the result by. Expected like this:
     * array(
     *  'foo' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
     *  'bar' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
     * )
     *
     * @param array<string,string> $orderings The property names to order by
     * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
     * @phpstan-return QueryInterface<T>
     */
    public function setOrderings(array $orderings);

    /**
     * Sets the maximum size of the result set to limit. Returns $this to allow
     * for chaining (fluid interface).
     *
     * @param int $limit
     * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
     * @phpstan-return QueryInterface<T>
     */
    public function setLimit($limit);

    /**
     * Sets the start offset of the result set to offset. Returns $this to
     * allow for chaining (fluid interface).
     *
     * @param int $offset
     * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
     * @phpstan-return QueryInterface<T>
     */
    public function setOffset($offset);

    /**
     * The constraint used to limit the result set. Returns $this to allow
     * for chaining (fluid interface).
     *
     * @param ConstraintInterface $constraint Some constraint, depending on the backend
     * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
     * @phpstan-return QueryInterface<T>
     */
    public function matching($constraint);

    /**
     * Performs a logical conjunction of the two given constraints. The method
     * takes an arbitrary number of constraints and concatenates them with a boolean AND.
     */
    public function logicalAnd(ConstraintInterface ...$constraints): AndInterface;

    /**
     * Performs a logical disjunction of the two given constraints. The method
     * takes an arbitrary number of constraints and concatenates them with a boolean OR.
     */
    public function logicalOr(ConstraintInterface ...$constraints): OrInterface;

    /**
     * Performs a logical negation of the given constraint
     *
     * @param ConstraintInterface $constraint Constraint to negate
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Qom\NotInterface
     */
    public function logicalNot(ConstraintInterface $constraint);

    /**
     * Returns an equals criterion used for matching objects against a query.
     *
     * It matches if the $operand equals the value of the property named
     * $propertyName. If $operand is NULL a strict check for NULL is done. For
     * strings the comparison can be done with or without case-sensitivity.
     *
     * @param string $propertyName The name of the property to compare against
     * @param mixed $operand The value to compare with
     * @param bool $caseSensitive Whether the equality test should be done case-sensitive for strings
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Qom\ComparisonInterface
     */
    public function equals($propertyName, $operand, $caseSensitive = true);

    /**
     * Returns a like criterion used for matching objects against a query.
     * Matches if the property named $propertyName is like the $operand, using
     * standard SQL wildcards.
     *
     * @param string $propertyName The name of the property to compare against
     * @param string $operand The value to compare with
     * @return ComparisonInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException if used on a non-string property
     */
    public function like($propertyName, $operand);

    /**
     * Returns a "contains" criterion used for matching objects against a query.
     * It matches if the multivalued property contains the given operand.
     *
     * If NULL is given as $operand, there will never be a match!
     *
     * @param string $propertyName The name of the multivalued property to compare against
     * @param mixed $operand The value to compare with
     * @return ComparisonInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException if used on a single-valued property
     */
    public function contains($propertyName, $operand);

    /**
     * Returns an "in" criterion used for matching objects against a query. It
     * matches if the property's value is contained in the multivalued operand.
     *
     * @param string $propertyName The name of the property to compare against
     * @param mixed $operand The value to compare with, multivalued
     * @return ComparisonInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException if used on a multi-valued property
     */
    public function in($propertyName, $operand);

    /**
     * Returns a less than criterion used for matching objects against a query
     *
     * @param string $propertyName The name of the property to compare against
     * @param mixed $operand The value to compare with
     * @return ComparisonInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException if used on a multi-valued property or with a non-literal/non-DateTime operand
     */
    public function lessThan($propertyName, $operand);

    /**
     * Returns a less or equal than criterion used for matching objects against a query
     *
     * @param string $propertyName The name of the property to compare against
     * @param mixed $operand The value to compare with
     * @return ComparisonInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException if used on a multi-valued property or with a non-literal/non-DateTime operand
     */
    public function lessThanOrEqual($propertyName, $operand);

    /**
     * Returns a greater than criterion used for matching objects against a query
     *
     * @param string $propertyName The name of the property to compare against
     * @param mixed $operand The value to compare with
     * @return ComparisonInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException if used on a multi-valued property or with a non-literal/non-DateTime operand
     */
    public function greaterThan($propertyName, $operand);

    /**
     * Returns a greater than or equal criterion used for matching objects against a query
     *
     * @param string $propertyName The name of the property to compare against
     * @param mixed $operand The value to compare with
     * @return ComparisonInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException if used on a multi-valued property or with a non-literal/non-DateTime operand
     */
    public function greaterThanOrEqual($propertyName, $operand);

    /**
     * Set the type this query cares for.
     * @phpstan-param class-string<T> $type
     */
    public function setType(string $type): void;

    /**
     * Returns the type this query cares for.
     *
     * @return string
     * @phpstan-return class-string<T>
     */
    public function getType();

    /**
     * Sets the Query Settings. These Query settings must match the settings expected by
     * the specific Storage Backend.
     */
    public function setQuerySettings(QuerySettingsInterface $querySettings);

    /**
     * Returns the Query Settings.
     *
     * @return QuerySettingsInterface $querySettings The Query Settings
     */
    public function getQuerySettings();

    /**
     * Returns the query result count.
     *
     * @return int The query result count
     */
    public function count();

    /**
     * Gets the property names to order the result by, like this:
     * array(
     *  'foo' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
     *  'bar' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
     * )
     *
     * @return array<string,string>
     */
    public function getOrderings();

    /**
     * Returns the maximum size of the result set to limit.
     *
     * @return int
     */
    public function getLimit();

    /**
     * Returns the start offset of the result set.
     *
     * @return int
     */
    public function getOffset();

    /**
     * Gets the constraint for this query.
     *
     * @return ConstraintInterface|null the constraint, or null if none
     */
    public function getConstraint();

    /**
     * Sets the source to fetch the result from
     */
    public function setSource(SourceInterface $source);

    /**
     * Returns the statement of this query.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Qom\Statement
     */
    public function getStatement();
}
