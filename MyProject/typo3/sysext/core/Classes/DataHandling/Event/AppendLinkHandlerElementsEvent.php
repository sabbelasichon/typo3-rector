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

namespace TYPO3\CMS\Core\DataHandling\Event;

/**
 * Event fired so listeners can intercept add elements when checking links within the SoftRef parser
 */
final class AppendLinkHandlerElementsEvent
{
    private bool $isResolved = false;

    public function __construct(
        private array $linkParts,
        private string $content,
        private array $elements,
        private readonly int $idx,
        private readonly string $tokenId
    ) {}

    public function getLinkParts(): array
    {
        return $this->linkParts;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getElements(): array
    {
        return $this->elements;
    }

    public function getIdx(): int
    {
        return $this->idx;
    }

    public function getTokenId(): string
    {
        return $this->tokenId;
    }

    public function setLinkParts(array $linkParts): void
    {
        $this->linkParts = $linkParts;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setElements(array $elements): void
    {
        $this->elements = $elements;
    }

    public function addElements(array $elements)
    {
        $this->elements = array_replace_recursive($this->elements, $elements);
        $this->isResolved = true;
    }

    public function isResolved(): bool
    {
        return $this->isResolved;
    }
}
