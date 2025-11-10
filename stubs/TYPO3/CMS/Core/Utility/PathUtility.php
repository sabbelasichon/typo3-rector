<?php

namespace TYPO3\CMS\Core\Utility;

if (class_exists('TYPO3\CMS\Core\Utility\PathUtility')) {
    return;
}

class PathUtility
{
    /**
     * @return string
     */
    public static function stripPathSitePrefix($path)
    {
    }

    public static function getAbsoluteWebPath(string $targetPath, bool $prefixWithSitePath = true): string
    {
    }
}
