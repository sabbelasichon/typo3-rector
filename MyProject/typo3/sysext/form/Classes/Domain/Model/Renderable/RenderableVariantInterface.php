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

namespace TYPO3\CMS\Form\Domain\Model\Renderable;

use TYPO3\CMS\Core\ExpressionLanguage\Resolver;

/**
 * Scope: frontend
 * **This class is NOT meant to be sub classed by developers.**
 * @internal
 */
interface RenderableVariantInterface
{
    public function getIdentifier(): string;

    /**
     * Apply the specified variant to this form element
     * regardless of their conditions
     */
    public function apply(): void;

    public function isApplied(): bool;

    public function conditionMatches(Resolver $conditionResolver): bool;
}
