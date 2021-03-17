<?php

declare(strict_types=1);

namespace TYPO3\CMS\Backend\Utility;

if (class_exists(BackendUtility::class)) {
    return;
}

final class BackendUtility
{
    public static function editOnClick($params, $_ = '', $requestUri = ''): string
    {
        return 'foo';
    }

    public static function getRecordRaw($table, $where = '', $fields = '*'): array
    {
        return [];
    }

    public static function TYPO3_copyRightNotice(): string
    {
        return 'notice';
    }

    public static function getModuleUrl($moduleName, $urlParameters = []): string
    {
        return 'foo';
    }

    public static function storeHash($hash, $data, $ident): void
    {
    }

    public static function getHash($hash): void
    {
    }

    public static function getViewDomain($pageId, $rootLine = null): string
    {
        return 'foo';
    }

    public static function wrapClickMenuOnIcon(
        $content,
        $table,
        $uid = 0,
        $context = '',
        $_addParams = '',
        $_enDisItems = '',
        $returnTagParameters = false
    ): void {

    }

    public static function thumbCode($row, $table, $field): string
    {
        return '';
    }

    public static function shortcutExists(string $url): bool
    {
        return true;
    }

    public static function getPidForModTSconfig($table, $uid, $pid): int
    {
        return 1;
    }

    public static function getPagesTSconfig($id, $rootLine = null, $returnPartArray = false): void
    {
    }

    public static function getRawPagesTSconfig($id, array $rootLine = null): void
    {
    }

    public static function getRecordsByField($theTable, $theField, $theValue, $whereClause = '', $groupBy = '', $orderBy = '', $limit = '', $useDeleteClause = true, $queryBuilder = null): array
    {
        return [];
    }

    public static function getClickMenuOnIconTagParameters(string $table, $uid = 0, string $context = '')
    {

    }
}
