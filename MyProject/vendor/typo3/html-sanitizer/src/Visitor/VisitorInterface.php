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

namespace TYPO3\HtmlSanitizer\Visitor;

use DOMNode;
use TYPO3\HtmlSanitizer\Context;

/**
 * Interface for custom node visitors.
 */
interface VisitorInterface
{
    /**
     * Executed before traversing any nodes
     * (e.g. used to initialize visitors)
     *
     * @param Context $context
     */
    public function beforeTraverse(Context $context);

    /**
     * Executed when entering a node level.
     * + returning `null` means "remove node"
     * + returning same `DOMNode` means "keep node"
     * + returning different `DOMNode` means "replace node"
     *
     * @param DOMNode $domNode
     * @return DOMNode|null
     */
    public function enterNode(DOMNode $domNode): ?DOMNode;

    /**
     * Executed when leaving a node level.
     * + returning `null` means "remove node"
     * + returning same `DOMNode` means "keep node"
     * + returning different `DOMNode` means "replace node"
     *
     * @param DOMNode $domNode
     * @return DOMNode|null
     */
    public function leaveNode(DOMNode $domNode): ?DOMNode;

    /**
     * Executed after having traversed all nodes
     * (e.g. used to finalize visitors)
     *
     * @param Context $context
     */
    public function afterTraverse(Context $context);
}
