<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Persistence\Generic;

use TYPO3\CMS\Extbase\Persistence\Generic\Qom\AndInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\OrInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

if (class_exists('TYPO3\CMS\Extbase\Persistence\Generic\Query')) {
    return;
}

final class Query implements QueryInterface
{
    /**
     * @param bool $returnRawQueryResult
     * @return QueryResultInterface|object[]
     */
    public function execute($returnRawQueryResult = false)
    {
    }

    public function setOrderings(array $orderings)
    {
    }

    public function setLimit($limit)
    {
    }

    public function setOffset($offset)
    {
    }

    /**
     * @return QueryInterface
     */
    public function matching($constraint)
    {
    }

    /**
     * @param ConstraintInterface ...$constraints
     * @return AndInterface
     */
    public function logicalAnd($constraints)
    {
    }

    /**
     * @param ConstraintInterface ...$constraints
     * @return OrInterface
     */
    public function logicalOr($constraints)
    {
    }

    public function logicalNot(ConstraintInterface $constraint)
    {
    }

    /**
     * Returns an equals criterion used for matching objects against a query
     *
     * @param string $propertyName The name of the property to compare against
     * @param mixed $operand The value to compare with
     * @param bool $caseSensitive Whether the equality test should be done case-sensitive
     * @return \TYPO3\CMS\Extbase\Persistence\Generic\Qom\ComparisonInterface
     */
    public function equals($propertyName, $operand, $caseSensitive = true)
    {
    }

    public function like($propertyName, $operand)
    {
    }

    public function contains($propertyName, $operand)
    {
    }

    public function in($propertyName, $operand)
    {
    }

    public function lessThan($propertyName, $operand)
    {
    }

    public function lessThanOrEqual($propertyName, $operand)
    {
    }

    public function greaterThan($propertyName, $operand)
    {
    }

    public function greaterThanOrEqual($propertyName, $operand)
    {
    }

    public function setType(string $type): void
    {
    }

    public function getType()
    {
    }

    public function setQuerySettings(QuerySettingsInterface $querySettings)
    {
    }

    /**
     * @return QuerySettingsInterface
     */
    public function getQuerySettings()
    {
    }

    public function count()
    {
    }

    public function getOrderings()
    {
    }

    public function getLimit()
    {
    }

    public function getOffset()
    {
    }

    public function getConstraint()
    {
    }

    public function getStatement()
    {
    }
}
