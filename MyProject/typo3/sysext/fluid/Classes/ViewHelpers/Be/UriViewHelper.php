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

namespace TYPO3\CMS\Fluid\ViewHelpers\Be;

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * A ViewHelper for creating URIs to modules.
 *
 * Examples
 * ========
 *
 * URI to the web_ts module on page 92::
 *
 *    <f:be.uri route="web_ts" parameters="{id: 92}"/>
 *
 * ``/typo3/module/web/ts?token=b6e9c9f&id=92``
 *
 * Inline notation::
 *
 *    {f:be.uri(route: 'web_ts', parameters: '{id: 92}')}
 *
 * ``/typo3/module/web/ts?token=b6e9c9f&id=92``
 */
final class UriViewHelper extends AbstractBackendViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('route', 'string', 'The name of the route', true);
        $this->registerArgument('parameters', 'array', 'An array of parameters', false, []);
        $this->registerArgument(
            'referenceType',
            'string',
            'The type of reference to be generated (one of the constants)',
            false,
            UriBuilder::ABSOLUTE_PATH
        );
    }

    public function render(): string
    {
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        $route = $this->arguments['route'];
        $parameters = $this->arguments['parameters'];
        $referenceType = $this->arguments['referenceType'];
        $uri = $uriBuilder->buildUriFromRoute($route, $parameters, $referenceType);
        return (string)$uri;
    }
}
