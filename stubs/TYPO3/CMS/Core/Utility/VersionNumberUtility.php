<?php
declare(strict_types=1);


namespace TYPO3\CMS\Core\Utility;

if (class_exists('TYPO3\CMS\Core\Utility\VersionNumberUtility')) {
    return;
}

class VersionNumberUtility
{
    public static function convertVersionNumberToInteger($verNumberStr): int
    {
        return 1;
    }
}
