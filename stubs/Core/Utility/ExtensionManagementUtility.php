<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Utility;

if (class_exists(ExtensionManagementUtility::class)) {
    return;
}

final class ExtensionManagementUtility
{
    public static function isLoaded($key, $exitOnError = null): bool
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

    public static function findService($serviceType, $serviceSubType = '', $excludeServiceKeys = []): string
    {
        return 'foo';
    }

    public static function configureModule($moduleSignature, $modulePath): array
    {
        return [];
    }

    public static function loadExtLocalconf(bool $false): void
    {
    }

    public static function extRelPath(string $key): void
    {
    }

    /**
     * Gets the TCA configuration for a field handling (FAL) files.
     *
     * @param string $fieldName Name of the field to be used
     * @param array $customSettingOverride Custom field settings overriding the basics
     * @param string $allowedFileExtensions Comma list of allowed file extensions (e.g. "jpg,gif,pdf")
     * @param string $disallowedFileExtensions
     *
     * @return array
     */
    public static function getFileFieldTCAConfig($fieldName, array $customSettingOverride = [], $allowedFileExtensions = '', $disallowedFileExtensions = '')
    {
        return [];
    }
}
