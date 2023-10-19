<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Persistence;

use TYPO3\CMS\Extbase\Persistence\Generic\Qom\AndInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\OrInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;

if (interface_exists('TYPO3\CMS\Extbase\Persistence\QueryInterface')) {
    return;
}

interface QueryInterface
{
    public const OPERATOR_EQUAL_TO = 1;

    public const OPERATOR_EQUAL_TO_NULL = 101;

    public const OPERATOR_NOT_EQUAL_TO = 2;

    public const OPERATOR_NOT_EQUAL_TO_NULL = 202;

    public const OPERATOR_LESS_THAN = 3;

    public const OPERATOR_LESS_THAN_OR_EQUAL_TO = 4;

    public const OPERATOR_GREATER_THAN = 5;

    public const OPERATOR_GREATER_THAN_OR_EQUAL_TO = 6;

    public const OPERATOR_LIKE = 7;

    public const OPERATOR_CONTAINS = 8;

    public const OPERATOR_IN = 9;

    public const OPERATOR_IS_NULL = 10;

    public const OPERATOR_IS_EMPTY = 11;

    public const ORDER_ASCENDING = 'ASC';
    public const ORDER_DESCENDING = 'DESC';

    /**
     * Executes the query and returns the result.
     *
     * @param bool $returnRawQueryResult
     * @return QueryResultInterface|object[]
     */
    public function execute($returnRawQueryResult = false);

    public function setOrderings(array $orderings);

    public function setLimit($limit);

    public function setOffset($offset);

    public function matching($constraint);

    /**
     * @param ConstraintInterface ...$constraints
     * @return AndInterface
     */
    public function logicalAnd($constraints);

    /**
     * @param ConstraintInterface ...$constraints
     * @return OrInterface
     */
    public function logicalOr($constraints);

    public function logicalNot(ConstraintInterface $constraint);

    public function equals($propertyName, $operand, $caseSensitive = true);

    public function like($propertyName, $operand);

    public function contains($propertyName, $operand);

    public function in($propertyName, $operand);

    public function lessThan($propertyName, $operand);

    public function lessThanOrEqual($propertyName, $operand);

    public function greaterThan($propertyName, $operand);

    public function greaterThanOrEqual($propertyName, $operand);

    public function setType(string $type): void;

    public function getType();

    public function setQuerySettings(QuerySettingsInterface $querySettings);

    public function getQuerySettings();

    public function count();

    public function getOrderings();

    public function getLimit();

    public function getOffset();

    public function getConstraint();

    public function getStatement();
}
