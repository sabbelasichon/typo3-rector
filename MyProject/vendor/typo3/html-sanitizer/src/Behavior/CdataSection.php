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

use DOMNode;
use DOMText;
use TYPO3\HtmlSanitizer\Behavior;
use TYPO3\HtmlSanitizer\Context;

/**
 * Model of CDATA node.
 */
class CdataSection implements NodeInterface, HandlerInterface
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
        return '#cdata-section';
    }

    public function handle(NodeInterface $node, ?DOMNode $domNode, Context $context, Behavior $behavior = null): ?DOMNode
    {
        if (!$this->secure || $domNode === null) {
            return $domNode;
        }
        return new DOMText(trim($domNode->nodeValue));
    }
}
