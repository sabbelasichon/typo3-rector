<?php
declare(strict_types=1);


namespace TYPO3\CMS\Core\Utility;

if (class_exists(HttpUtility::class)) {
    return;
}

final class HttpUtility
{

    public static function buildQueryString($queryParams): string
    {
        return '';
    }
}
