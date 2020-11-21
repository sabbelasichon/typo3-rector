<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Database\Query;

if (class_exists(QueryHelper::class)) {
    return;
}

final class QueryHelper
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
