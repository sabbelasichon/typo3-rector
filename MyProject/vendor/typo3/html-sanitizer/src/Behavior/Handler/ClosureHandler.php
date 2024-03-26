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

namespace TYPO3\HtmlSanitizer\Behavior\Handler;

use Closure;
use DOMNode;
use LogicException;
use TYPO3\HtmlSanitizer\Behavior;
use TYPO3\HtmlSanitizer\Behavior\HandlerInterface;
use TYPO3\HtmlSanitizer\Behavior\NodeInterface;
use TYPO3\HtmlSanitizer\Context;

class ClosureHandler implements HandlerInterface
{
    /**
     * @var Closure
     */
    protected $closure;

    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    public function handle(NodeInterface $node, ?DOMNode $domNode, Context $context, Behavior $behavior = null): ?DOMNode
    {
        $result = call_user_func($this->closure, $node, $domNode, $context, $behavior);
        if ($result !== null && !$result instanceof DOMNode) {
            throw new LogicException('Closure must return either null or DOMNode', 1666342014);
        }
        return $result;
    }
}
