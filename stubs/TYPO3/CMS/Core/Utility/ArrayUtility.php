<?php

namespace TYPO3\CMS\Core\Utility;

if (class_exists('TYPO3\CMS\Core\Utility\ArrayUtility')) {
    return;
}

class ArrayUtility
{
    /**
     * @return void
     */
    public static function mergeRecursiveWithOverrule(array &$original, array $overrule, $addKeys = true, $includeEmptyValues = true, $enableUnsetFeature = true)
    {

    }

    /**
     * @return void
     */
    public static function getValueByPath()
    {

    }

    /**
     * @return void
     */
    public static function setValueByPath()
    {

    }

    /**
     * @return void
     */
    public static function removeByPath()
    {

    }

    /**
     * @return void
     */
    public static function sortArrayWithIntegerKeys()
    {

    }
    /**
     * @return bool
     */
    public static function inArray(array $in_array, $item)
    {
        return true;
    }

    /**
     * @return array
     */
    public static function arrayDiffAssocRecursive(array $array1, array $array2, bool $useArrayDiffAssocBehavior = false)
    {
        return [];
    }

    /**
     * @return array
     */
    public static function arrayDiffKeyRecursive(array $array1, array $array2)
    {
        return [];
    }

}
