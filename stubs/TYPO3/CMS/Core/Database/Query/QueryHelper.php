<?php


namespace TYPO3\CMS\Core\Database\Query;

if (class_exists(QueryHelper::class)) {
    return;
}

class QueryHelper
{
    public static function stripLogicalOperatorPrefix(string $whereClause): string
    {
        return 'foo';
    }

    public static function parseGroupBy(string $groupBy): array
    {
        return [];
    }

    public static function parseOrderBy(string $orderBy): array
    {
        return [];
    }
}
