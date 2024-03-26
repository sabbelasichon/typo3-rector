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

/*
 * Inspired by and partially taken from the Neos.Form package (www.neos.io)
 */

namespace TYPO3\CMS\Form\Domain\Model\Renderable;

/**
 * Scope: frontend
 * **This class is NOT meant to be sub classed by developers.**
 */
interface VariableRenderableInterface
{
    /**
     * Set multiple properties of this object at once.
     * Every property which has a corresponding set* method can be set using
     * the passed $options array.
     *
     * @internal
     */
    public function setOptions(array $options, bool $reset = false);

    /**
     * Get all rendering variants
     *
     * @return RenderableVariantInterface[]
     */
    public function getVariants(): array;

    /**
     * Adds the specified variant to this form element
     */
    public function addVariant(RenderableVariantInterface $variant);
}
