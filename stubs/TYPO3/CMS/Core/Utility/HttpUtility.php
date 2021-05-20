<?php
declare(strict_types=1);


namespace TYPO3\CMS\Core\Utility;

if (class_exists('TYPO3\CMS\Core\Utility\HttpUtility')) {
    return;
}

class HttpUtility
{

    const HTTP_STATUS_400 = 'HTTP/1.1 400 Bad Request';

    public static function buildQueryString(array $queryParams): string
    {
        return '';
    }

    public static function setResponseCode(string $httpStatus): void
    {

    }
}
