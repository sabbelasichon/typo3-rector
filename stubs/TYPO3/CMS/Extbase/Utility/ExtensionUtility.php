<?php

namespace TYPO3\CMS\Extbase\Utility;

if (class_exists('TYPO3\CMS\Extbase\Utility\ExtensionUtility')) {
    return;
}

class ExtensionUtility
{
    /**
     * @var string
     */
    const PLUGIN_TYPE_PLUGIN = 'list_type';

    /**
     * @var string
     */
    const PLUGIN_TYPE_CONTENT_ELEMENT = 'CType';

    /**
     * @return void
     */
    public static function registerPlugin($extensionName, $pluginName, $pluginTitle, $pluginIcon = null)
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
     */
    public static function registerModule($extensionName, $mainModuleName = '', $subModuleName = '', $position = '', array $controllerActions = [], array $moduleConfiguration = [])
    {
    }

    /**
     * @return void
     */
    public static function configurePlugin($extensionName, $pluginName, array $controllerActions, array $nonCacheableControllerActions = [], $pluginType = self::PLUGIN_TYPE_PLUGIN)
    {
    }
}
