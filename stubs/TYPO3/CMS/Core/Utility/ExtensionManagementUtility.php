<?php

namespace TYPO3\CMS\Core\Utility;

use TYPO3\CMS\Core\Schema\Struct\SelectItem;

if (class_exists('TYPO3\CMS\Core\Utility\ExtensionManagementUtility')) {
    return;
}

class ExtensionManagementUtility
{
    /**
     * @return bool
     */
    public static function isLoaded($key, $exitOnError = null)
    {
        return true;
    }

    public static function siteRelPath($key)
    {
        return $key;
    }

    public static function extPath($key)
    {
        return $key;
    }

    public static function removeCacheFiles()
    {
        return null;
    }

    /**
     * @return string
     */
    public static function findService($serviceType, $serviceSubType = '', $excludeServiceKeys = [])
    {
        return 'foo';
    }

    /**
     * @return void
     */
    public static function addModule($main, $sub = '', $position = '', $path = null, $moduleConfiguration = [])
    {
    }

    /**
     * @return mixed[]
     */
    public static function configureModule($moduleSignature, $modulePath)
    {
        return [];
    }

    /**
     * @return void
     * @param bool $false
     */
    public static function loadExtLocalconf($false)
    {
    }

    /**
     * @return void
     * @param string $key
     */
    public static function extRelPath($key)
    {
    }

    /**
     * @return void
     */
    public static function addStaticFile($extKey, $path, $title)
    {
    }

    /**
     * @param string $fieldName
     * @param array $customSettingOverride
     * @param string $allowedFileExtensions
     * @param string $disallowedFileExtensions
     * @return array
     */
    public static function getFileFieldTCAConfig($fieldName, array $customSettingOverride = [], $allowedFileExtensions = '', $disallowedFileExtensions = '')
    {
        return [];
    }

    /**
     * @param string $string
     */
    public static function addTCAcolumns($string, array $columns)
    {

    }

    public static function allowTableOnStandardPages(string $table)
    {

    }

    public static function addToAllTCAtypes(string $table, string $newFieldsString, string $typeList = '', string $position = ''): void
    {
    }

    public static function addPageTSConfig(string $content): void
    {
    }

    public static function addUserTSConfig(string $content): void
    {
    }

    public static function addPlugin($itemArray, string $type = 'list_type', ?string $extensionKey = null): void
    {
    }

    public static function addPiFlexFormValue(string $piKeyToMatch, string $value, string $CTypeToMatch = 'list'): void
    {
    }

    public static function addTcaSelectItem(string $table, string $field, $item, string $relativeToField = '', string $relativePosition = ''): void
    {
    }
}
