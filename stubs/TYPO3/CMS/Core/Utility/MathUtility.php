<?php
namespace TYPO3\CMS\Core\Utility;

if (class_exists('TYPO3\CMS\Core\Utility\MathUtility')) {
    return;
}

class MathUtility
{

    /**
     * @return bool
     */
    public static function canBeInterpretedAsInteger($uid)
    {
        return true;
    }

    public static function convertToPositiveInteger($theInt): int
    {
        return $theInt;
    }
}
