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

namespace TYPO3\CMS\Core\Routing\Enhancer;

use TYPO3\CMS\Core\Routing\RouteCollection;

/**
 * Interface for enhancers
 */
interface RoutingEnhancerInterface extends EnhancerInterface
{
    /**
     * Extends route collection with all routes. Used during URL resolving.
     */
    public function enhanceForMatching(RouteCollection $collection): void;

    /**
     * Extends route collection with routes that are relevant for given
     * parameters. Used during URL generation.
     */
    public function enhanceForGeneration(RouteCollection $collection, array $parameters): void;
}
