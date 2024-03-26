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

namespace TYPO3\CMS\Core\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Handling requirejs client requests.
 *
 * @deprecated will be removed in TYPO3 v13.0.
 */
class RequireJsController
{
    /**
     * @var PageRenderer
     */
    protected $pageRenderer;

    public function __construct()
    {
        $this->pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
    }

    /**
     * Retrieves additional requirejs configuration for a given module name or module path.
     *
     * The JSON result e.g. could look like:
     * {
     *   "shim": {
     *     "vendor/module": ["exports" => "TheModule"]
     *   },
     *   "paths": {
     *     "vendor/module": "/public/web/path/"
     *   },
     *   "packages": {
     *     [
     *       "name": "module",
     *       ...
     *     ]
     *   }
     * }
     *
     * Parameter name either could be the module name ("vendor/module") or a
     * module path ("vendor/module/component") belonging to a module.
     *
     * @param ServerRequestInterface $request
     */
    public function retrieveConfiguration(ServerRequestInterface $request): ResponseInterface
    {
        $name = $request->getQueryParams()['name'] ?? null;
        if (empty($name) || !is_string($name)) {
            return new JsonResponse(null, 404);
        }
        $configuration = $this->findConfiguration($name);
        return new JsonResponse($configuration, !empty($configuration) ? 200 : 404);
    }

    protected function findConfiguration(string $name): array
    {
        $relevantConfiguration = [];
        $this->pageRenderer->loadRequireJs();
        $configuration = $this->pageRenderer->getRequireJsConfig(PageRenderer::REQUIREJS_SCOPE_RESOLVE);

        $shim = $configuration['shim'] ?? [];
        foreach ($shim as $baseModuleName => $baseModuleConfiguration) {
            if (str_starts_with($name . '/', $baseModuleName . '/')) {
                $relevantConfiguration['shim'][$baseModuleName] = $baseModuleConfiguration;
            }
        }

        $paths = $configuration['paths'] ?? [];
        foreach ($paths as $baseModuleName => $baseModulePath) {
            if (str_starts_with($name . '/', $baseModuleName . '/')) {
                $relevantConfiguration['paths'][$baseModuleName] = $baseModulePath;
            }
        }

        $packages = $configuration['packages'] ?? [];
        foreach ($packages as $package) {
            if (!empty($package['name'])
                && str_starts_with($name . '/', $package['name'] . '/')
            ) {
                $relevantConfiguration['packages'][] = $package;
            }
        }

        return $relevantConfiguration;
    }
}
