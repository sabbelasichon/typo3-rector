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

namespace TYPO3\CMS\T3editor;

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Package\Cache\PackageDependentCacheIdentifier;
use TYPO3\CMS\Core\Package\PackageManager;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\T3editor\Registry\AddonRegistry;
use TYPO3\CMS\T3editor\Registry\ModeRegistry;

/**
 * Provides necessary code to setup a t3editor instance in FormEngine
 * @internal
 * @todo: refactor to use DI
 */
class T3editor implements SingletonInterface
{
    /**
     * @var array
     */
    protected $configuration;

    /**
     * Registers the configuration and bootstraps the modes / addons.
     *
     * @throws \InvalidArgumentException
     */
    public function registerConfiguration()
    {
        $configuration = $this->buildConfiguration();

        if (isset($configuration['modes'])) {
            $modeRegistry = GeneralUtility::makeInstance(ModeRegistry::class);
            foreach ($configuration['modes'] as $formatCode => $mode) {
                $modeInstance = GeneralUtility::makeInstance(Mode::class, $mode['module'])->setFormatCode($formatCode);

                if (!empty($mode['extensions']) && is_array($mode['extensions'])) {
                    $modeInstance->bindToFileExtensions($mode['extensions']);
                }

                if (isset($mode['default']) && $mode['default'] === true) {
                    $modeInstance->setAsDefault();
                }

                $modeRegistry->register($modeInstance);
            }
        }

        $addonRegistry = GeneralUtility::makeInstance(AddonRegistry::class);
        if (isset($configuration['addons'])) {
            foreach ($configuration['addons'] as $identifier => $addon) {
                $addonInstance = GeneralUtility::makeInstance(Addon::class, $identifier, $addon['module'] ?? null, $addon['keymap'] ?? null);

                if (!empty($addon['cssFiles']) && is_array($addon['cssFiles'])) {
                    $addonInstance->setCssFiles($addon['cssFiles']);
                }

                if (!empty($addon['options']) && is_array($addon['options'])) {
                    $addonInstance->setOptions($addon['options']);
                }

                $addonRegistry->register($addonInstance);
            }
        }
    }

    /**
     * Compiles the configuration for t3editor. Configuration is stored in caching framework.
     *
     * @throws \TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException
     * @throws \TYPO3\CMS\Core\Cache\Exception\InvalidDataException
     * @throws \InvalidArgumentException
     */
    protected function buildConfiguration(): array
    {
        if ($this->configuration !== null) {
            return $this->configuration;
        }

        $this->configuration = [
            'modes' => [],
            'addons' => [],
        ];

        $cache = $this->getCache();
        $packageManager = GeneralUtility::makeInstance(PackageManager::class);
        $cacheIdentifier = $this->generateCacheIdentifier($packageManager);
        $configurationFromCache = $cache->get($cacheIdentifier);
        if ($configurationFromCache !== false) {
            $this->configuration = $configurationFromCache;
        } else {
            $packages = $packageManager->getActivePackages();

            foreach ($packages as $package) {
                $configurationPath = $package->getPackagePath() . 'Configuration/Backend/T3editor';
                $modesFileNameForPackage = $configurationPath . '/Modes.php';
                if (is_file($modesFileNameForPackage)) {
                    $definedModes = require_once $modesFileNameForPackage;
                    if (is_array($definedModes)) {
                        $this->configuration['modes'] = array_merge($this->configuration['modes'], $definedModes);
                    }
                }

                $addonsFileNameForPackage = $configurationPath . '/Addons.php';
                if (is_file($addonsFileNameForPackage)) {
                    $definedAddons = require_once $addonsFileNameForPackage;
                    if (is_array($definedAddons)) {
                        $this->configuration['addons'] = array_merge($this->configuration['addons'], $definedAddons);
                    }
                }
            }
            $cache->set($cacheIdentifier, $this->configuration);
        }

        return $this->configuration;
    }

    protected function generateCacheIdentifier(PackageManager $packageManager): string
    {
        return (new PackageDependentCacheIdentifier($packageManager))->withPrefix('T3editorConfiguration')->toString();
    }

    /**
     * @throws \TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException
     * @throws \InvalidArgumentException
     */
    protected function getCache(): FrontendInterface
    {
        return GeneralUtility::makeInstance(CacheManager::class)->getCache('assets');
    }
}
