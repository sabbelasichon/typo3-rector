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

use DOMComment;
use DOMNode;
use TYPO3\HtmlSanitizer\Behavior;
use TYPO3\HtmlSanitizer\Context;

/**
 * Model of comment node.
 */
class Comment implements NodeInterface, HandlerInterface
{
    /**
     * @var bool
     */
    protected $secure = true;

    public function __construct(bool $secure = true)
    {
        $this->secure = $secure;
    }

    public function getName(): string
    {
        return '#comment';
    }

    public function handle(NodeInterface $node, ?DOMNode $domNode, Context $context, Behavior $behavior = null): ?DOMNode
    {
        if (!$this->secure || $domNode === null) {
            return $domNode;
        }
        return new DOMComment(htmlspecialchars($domNode->textContent, ENT_QUOTES, 'UTF-8', false));
    }
}
