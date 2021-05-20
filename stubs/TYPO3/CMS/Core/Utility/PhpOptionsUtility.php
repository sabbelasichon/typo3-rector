<?php
declare(strict_types=1);


namespace TYPO3\CMS\Core\Utility;

if (class_exists('TYPO3\CMS\Core\Utility\PhpOptionsUtility')) {
    return;
}

class PhpOptionsUtility
{
    public static function isSessionAutoStartEnabled(): bool
    {
        return self::getIniValueBoolean('session.auto_start');
    }

    public static function getIniValueBoolean($configOption)
    {
        return filter_var(ini_get($configOption), FILTER_VALIDATE_BOOLEAN, [FILTER_REQUIRE_SCALAR, FILTER_NULL_ON_FAILURE]);
    }
}
