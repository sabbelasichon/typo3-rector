<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Persistence\Generic;

use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
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

    public function matching($constraint)
    {
    }

    public function logicalAnd($constraint1)
    {
    }

    public function logicalOr($constraint1)
    {
    }

    public function logicalNot(ConstraintInterface $constraint)
    {
    }

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
