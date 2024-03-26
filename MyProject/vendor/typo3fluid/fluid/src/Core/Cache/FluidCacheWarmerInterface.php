<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Cache;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Interface FluidCacheWarmerInterface
 *
 * Implemented by classes providing cache warmup
 * for Fluid templates. Please see the provided
 * StandardCacheWarmer implementation of this interface
 * for more detailed explanations about warmup.
 */
interface FluidCacheWarmerInterface
{
    /**
     * Warm up an entire collection of templates based on the
     * provided RenderingContext. Returns a FluidCacheWarmupResult
     * with feedback about the templates that were processed.
     *
     * Resolving, compiling, reporting and error handling is
     * completely up to the implementing class. Standard file based
     * template resolving and compiling can be inherited by subclassing
     * the provided StandardCacheWarmer and overriding methods.
     *
     * @param RenderingContextInterface $renderingContext
     * @return FluidCacheWarmupResult
     */
    public function warm(RenderingContextInterface $renderingContext);
}
