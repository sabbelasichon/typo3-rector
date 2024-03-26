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

namespace TYPO3\CMS\Dashboard\Widgets\Provider;

use TYPO3\CMS\Dashboard\Widgets\ButtonProviderInterface;

/**
 * Provides a button for the footer of a widget
 */
class ButtonProvider implements ButtonProviderInterface
{
    public function __construct(
        private readonly string $title,
        private readonly string $link,
        private readonly string $target = ''
    ) {}

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getTarget(): string
    {
        return $this->target;
    }
}
