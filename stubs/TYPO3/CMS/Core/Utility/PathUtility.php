<?php


namespace TYPO3\CMS\Core\Utility;

if (class_exists(PathUtility::class)) {
    return;
}

class PathUtility
{
    public static function stripPathSitePrefix($path)
    {
        return $path;
    }

    public static function getAbsoluteWebPath($path)
    {
        return $path;
    }
}
