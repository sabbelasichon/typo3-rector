<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\CMS\Form\Mvc\Configuration;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager as ExtbaseConfigurationManager;
use TYPO3\CMS\Form\Mvc\Configuration\Exception\ExtensionNameRequiredException;

/**
 * Extend the ExtbaseConfigurationManager to read YAML configurations.
 *
 * Scope: frontend / backend
 * @internal
 */
class ConfigurationManager extends ExtbaseConfigurationManager implements ConfigurationManagerInterface
{
    /**
     * @var FrontendInterface
     */
    protected $cache;

    /**
     * @var \TYPO3\CMS\Form\Mvc\Configuration\YamlSource
     */
    protected $yamlSource;

    /**
     * @internal
     */
    public function injectYamlSource(YamlSource $yamlSource)
    {
        $this->yamlSource = $yamlSource;
    }

    /**
     * @param string $configurationType The kind of configuration to fetch - must be one of the CONFIGURATION_TYPE_* constants
     * @param string $extensionName if specified, the configuration for the given extension will be returned.
     * @param string $pluginName if specified, the configuration for the given plugin will be returned.
     * @return array The configuration
     * @internal
     */
    public function getConfiguration(string $configurationType, string $extensionName = null, string $pluginName = null): array
    {
        switch ($configurationType) {
            case self::CONFIGURATION_TYPE_YAML_SETTINGS:
                return $this->getConfigurationFromYamlFile($extensionName);
            default:
                return parent::getConfiguration($configurationType, $extensionName, $pluginName);
        }
    }

    /**
     * Load and parse YAML files which are configured within the TypoScript
     * path plugin.tx_extensionkey.settings.yamlConfigurations
     *
     * The following steps will be done:
     *
     * * Convert each singe YAML file into an array
     * * merge this arrays together
     * * resolve all declared inheritances
     * * remove all keys if their values are NULL
     * * return all configuration paths within TYPO3.CMS
     * * sort by array keys, if all keys within the current nesting level are numerical keys
     * * resolve possible TypoScript settings in FE mode
     *
     * @param string $extensionName
     * @throws ExtensionNameRequiredException
     */
    protected function getConfigurationFromYamlFile(string $extensionName): array
    {
        if (empty($extensionName)) {
            throw new ExtensionNameRequiredException(
                'Please specify an extension key to load a YAML configuration',
                1471473377
            );
        }

        $typoscriptSettings = $this->getTypoScriptSettings($extensionName);

        $yamlSettingsFilePaths = isset($typoscriptSettings['yamlConfigurations'])
            ? ArrayUtility::sortArrayWithIntegerKeys($typoscriptSettings['yamlConfigurations'])
            : [];

        $cacheKeySuffix = $extensionName . md5(json_encode($yamlSettingsFilePaths));

        $yamlSettings = $this->getYamlSettingsFromCache($cacheKeySuffix);
        if (!empty($yamlSettings)) {
            return $this->overrideConfigurationByTypoScript($yamlSettings, $extensionName);
        }

        $yamlSettings = InheritancesResolverService::create($this->yamlSource->load($yamlSettingsFilePaths))
            ->getResolvedConfiguration();

        $yamlSettings = ArrayUtility::removeNullValuesRecursive($yamlSettings);
        $yamlSettings = ArrayUtility::sortArrayWithIntegerKeysRecursive($yamlSettings);
        $this->setYamlSettingsIntoCache($cacheKeySuffix, $yamlSettings);

        return $this->overrideConfigurationByTypoScript($yamlSettings, $extensionName);
    }

    protected function overrideConfigurationByTypoScript(array $yamlSettings, string $extensionName): array
    {
        $typoScript = parent::getConfiguration(self::CONFIGURATION_TYPE_SETTINGS, $extensionName);
        if (is_array($typoScript['yamlSettingsOverrides'] ?? null) && !empty($typoScript['yamlSettingsOverrides'])) {
            $yamlSettingsOverrides = $typoScript['yamlSettingsOverrides'];
            if (($GLOBALS['TYPO3_REQUEST'] ?? null) instanceof ServerRequestInterface
                && ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend()
            ) {
                $yamlSettingsOverrides = GeneralUtility::makeInstance(TypoScriptService::class)
                    ->resolvePossibleTypoScriptConfiguration($yamlSettingsOverrides);
            }

            ArrayUtility::mergeRecursiveWithOverrule(
                $yamlSettings,
                $yamlSettingsOverrides
            );
        }
        return $yamlSettings;
    }

    protected function getCacheFrontend(): FrontendInterface
    {
        if ($this->cache === null) {
            $this->cache = GeneralUtility::makeInstance(CacheManager::class)->getCache('assets');
        }
        return $this->cache;
    }

    protected function getConfigurationCacheKey(string $cacheKeySuffix): string
    {
        return strtolower(self::CONFIGURATION_TYPE_YAML_SETTINGS . '_' . $cacheKeySuffix);
    }

    /**
     * @return mixed
     */
    protected function getYamlSettingsFromCache(string $cacheKeySuffix)
    {
        return $this->getCacheFrontend()->get(
            $this->getConfigurationCacheKey($cacheKeySuffix)
        );
    }

    protected function setYamlSettingsIntoCache(
        string $cacheKeySuffix,
        array $yamlSettings
    ) {
        $this->getCacheFrontend()->set(
            $this->getConfigurationCacheKey($cacheKeySuffix),
            $yamlSettings
        );
    }

    /**
     * @return array
     */
    protected function getTypoScriptSettings(string $extensionName)
    {
        return parent::getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            $extensionName
        );
    }
}
