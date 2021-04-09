<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Utility;

if (class_exists(ExtensionUtility::class)) {
    return;
}

final class ExtensionUtility
{
    /**
     * @var string
     */
    public const PLUGIN_TYPE_PLUGIN = 'list_type';

    /**
     * @var string
     */
    public const PLUGIN_TYPE_CONTENT_ELEMENT = 'CType';

    public static function registerPlugin($extensionName, $pluginName, $pluginTitle, $pluginIcon = null): void
    {
    }

    public static function configureModule($moduleSignature, $modulePath): array
    {
        return [];
    }

    public static function registerModule($extensionName, $mainModuleName = '', $subModuleName = '', $position = '', array $controllerActions = [], array $moduleConfiguration = []): void
    {
    }

    public static function configurePlugin($extensionName, $pluginName, array $controllerActions, array $nonCacheableControllerActions = [], $pluginType = self::PLUGIN_TYPE_PLUGIN): void
    {
    }
}
