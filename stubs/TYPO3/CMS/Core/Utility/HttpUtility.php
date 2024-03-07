<?php
namespace TYPO3\CMS\Core\Utility;

if (class_exists('TYPO3\CMS\Core\Utility\HttpUtility')) {
    return;
}

class HttpUtility
{

    const HTTP_STATUS_400 = 'HTTP/1.1 400 Bad Request';
    public const HTTP_STATUS_303 = 'HTTP/1.1 303 See Other';

    /**
     * @return string
     */
    public static function buildQueryString(array $queryParams)
    {
        return '';
    }

    /**
     * @return void
     * @param string $httpStatus
     */
    public static function setResponseCode($httpStatus)
    {

    }

    public static function redirect($url, $httpStatus = self::HTTP_STATUS_303)
    {
    }
}
