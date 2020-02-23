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

    public static function getModuleUrl($moduleName, $urlParameters = [])
    {
        return 'foo';
    }
}
