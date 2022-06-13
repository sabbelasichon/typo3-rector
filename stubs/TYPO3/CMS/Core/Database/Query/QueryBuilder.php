<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Database\Query;

use Doctrine\DBAL\Result;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder;

if (class_exists('TYPO3\CMS\Core\Database\Query\QueryBuilder')) {
    return;
}

class QueryBuilder
{
    /**
     * @var Connection
     */
    protected $connection;

    public function expr(): ExpressionBuilder
    {
        return $this->connection->getExpressionBuilder();
    }

    public function select(string ...$selects): QueryBuilder
    {
        return $this;
    }

    public function from(string $from, string $alias = null): QueryBuilder
    {
        return $this;
    }

    public function where(...$where): QueryBuilder
    {
        return $this;
    }

    public function createNamedParameter($value, int $type = \PDO::PARAM_STR, string $placeHolder = null): string
    {
        return '';
    }

    public function executeQuery(): Result
    {
        return new Result();
    }
}
