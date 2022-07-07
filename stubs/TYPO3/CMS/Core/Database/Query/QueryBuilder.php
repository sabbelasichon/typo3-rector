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

    public function update(string $table): QueryBuilder
    {
        return $this;
    }

    public function count(string $table): QueryBuilder
    {
        return $this;
    }

    public function delete(string $table): QueryBuilder
    {
        return $this;
    }

    public function insert(string $table): QueryBuilder
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

    public function execute(): Result
    {
        return new Result();
    }

    public function executeStatement(): int
    {
        return 1;
    }

    public function set(string $field, string $value): QueryBuilder
    {
        return $this;
    }

    public function values(array $values, bool $createNamedParameters = true): QueryBuilder
    {
        return $this;
    }

    public function selectLiteral(string ...$selects): QueryBuilder
    {
        return $this;
    }

    public function addSelect(string ...$selects): QueryBuilder
    {
        return $this;
    }

    public function addSelectLiteral(string ...$selects): QueryBuilder
    {
        return $this;
    }
}
