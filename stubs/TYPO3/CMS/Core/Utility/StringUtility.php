<?php

namespace TYPO3\CMS\Core\Utility;

if (class_exists('TYPO3\CMS\Core\Utility\StringUtility')) {
    return;
}

class StringUtility
{
    public static function beginsWith($haystack, $needle)
    {
        return false;
    }

    public static function uniqueList($list)
    {
        return [];
    }

    public static function getUniqueId(string $string): string
    {
        return $string;
    }
}
