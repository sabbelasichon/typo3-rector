<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 project.
 *
 * It is free software; you can redistribute it and/or modify it under the terms
 * of the MIT License (MIT). For the full copyright and license information,
 * please read the LICENSE file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\HtmlSanitizer\Behavior;

class NodeHandler implements NodeInterface
{
    /**
     * Whether defaults shall be processed (e.g. verifying all attributes etc.)
     */
    public const PROCESS_DEFAULTS = 1;

    /**
     * Whether this handler shall be processed first (before processing defaults)
     */
    public const HANDLE_FIRST = 2;

    /**
     * @var NodeInterface
     */
    protected $node;

    /**
     * @var HandlerInterface
     */
    protected $handler;

    /**
     * @var int
     */
    protected $flags;

    public function __construct(NodeInterface $node, HandlerInterface $handler, int $flags = 0)
    {
        $this->node = $node;
        $this->handler = $handler;
        $this->flags = $flags;
    }

    public function getName(): string
    {
        return $this->node->getName();
    }

    public function getNode(): NodeInterface
    {
        return $this->node;
    }

    public function getHandler(): HandlerInterface
    {
        return $this->handler;
    }

    public function getFlags(): int
    {
        return $this->flags;
    }

    public function shallProcessDefaults(): bool
    {
        return ($this->flags & self::PROCESS_DEFAULTS) === self::PROCESS_DEFAULTS;
    }

    public function shallHandleFirst(): bool
    {
        return ($this->flags & self::HANDLE_FIRST) === self::HANDLE_FIRST;
    }
}
