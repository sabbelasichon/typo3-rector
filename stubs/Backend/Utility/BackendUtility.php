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

    public static function wrapClickMenuOnIcon($content, $table, $uid, $listFr, $addParams, $enDisItems, $returnTagParameters): void
    {

    }

    public static function shortcutExists(string $url): bool
    {
        return true;
    }
}
