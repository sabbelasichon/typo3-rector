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

namespace TYPO3\CMS\Backend\ContextMenu\ItemProviders;

/**
 * Interface for context menu items providers
 */
interface ProviderInterface
{
    public function addItems(array $items): array;

    /**
     * Returns the priority of the provider. Higher priority value means provider is executed first
     */
    public function getPriority(): int;

    /**
     * Checks if the provider can add items to the menu
     */
    public function canHandle(): bool;

    /**
     * Initialize the current context.
     * This method is called directly after fetching the provider from the container.
     */
    public function setContext(string $table, string $identifier, string $context = ''): void;
}
