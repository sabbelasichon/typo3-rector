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
}
