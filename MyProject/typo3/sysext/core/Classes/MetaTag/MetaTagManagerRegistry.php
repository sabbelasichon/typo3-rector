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

namespace TYPO3\CMS\Core\MetaTag;

use TYPO3\CMS\Core\Service\DependencyOrderingService;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Holds all available meta tag managers
 */
class MetaTagManagerRegistry implements SingletonInterface
{
    /**
     * @var mixed[]
     */
    protected $registry = [];

    /**
     * @var MetaTagManagerInterface[]
     */
    private $instances = [];

    /**
     * @var MetaTagManagerInterface[]|null
     */
    private $managers;

    public function __construct()
    {
        $this->registry['generic'] = [
            'module' => GenericMetaTagManager::class,
        ];
    }

    /**
     * Add a MetaTagManager to the registry
     */
    public function registerManager(string $name, string $className, array $before = ['generic'], array $after = [])
    {
        if (!count($before)) {
            $before[] = 'generic';
        }

        $this->registry[$name] = [
            'module' => $className,
            'before' => $before,
            'after' => $after,
        ];
        $this->managers = null;
    }

    /**
     * Get the MetaTagManager for a specific property
     */
    public function getManagerForProperty(string $property): MetaTagManagerInterface
    {
        $property = strtolower($property);
        foreach ($this->getAllManagers() as $manager) {
            if ($manager->canHandleProperty($property)) {
                return $manager;
            }
        }

        // Just a fallback because the GenericMetaTagManager is also registered in the list of MetaTagManagers
        return GeneralUtility::makeInstance(GenericMetaTagManager::class);
    }

    /**
     * Get an array of all registered MetaTagManagers
     *
     * @return MetaTagManagerInterface[]
     */
    public function getAllManagers(): array
    {
        if ($this->managers !== null) {
            return $this->managers;
        }

        $orderedManagers = GeneralUtility::makeInstance(DependencyOrderingService::class)->orderByDependencies(
            $this->registry
        );

        $this->managers = [];
        foreach ($orderedManagers as $manager => $managerConfiguration) {
            $module = $managerConfiguration['module'];
            if (class_exists($module)) {
                $this->instances[$module] = $this->instances[$module] ?? GeneralUtility::makeInstance($module);
                $this->managers[$manager] = $this->instances[$module];
            }
        }

        return $this->managers;
    }

    /**
     * Remove all registered MetaTagManagers
     */
    public function removeAllManagers()
    {
        $this->registry = [];
        $this->managers = null;
    }

    /**
     * @internal
     */
    public function updateState(array $state): void
    {
        $this->instances = [];
        foreach ($state['instances'] ?? [] as $module => $instanceState) {
            $instance = GeneralUtility::makeInstance($module);
            $instance->updateState($instanceState);
            $this->instances[$module] = $instance;
        }
        $this->managers = null;
    }

    /**
     * @internal
     */
    public function getState(): array
    {
        return [
            'instances' => array_map(
                static fn(MetaTagManagerInterface $instance): array => $instance->getState(),
                $this->instances,
            ),
        ];
    }
}
