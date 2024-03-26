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
use RuntimeException;

class NodeException extends RuntimeException
{
    public static function create(): self
    {
        return new self('<node>', 1624911897);
    }

    /**
     * @var DOMNode|null
     */
    protected $domNode;

    public function withDomNode(?DOMNode $domNode): self
    {
        $this->domNode = $domNode;
        return $this;
    }

    /**
     * @deprecated since v2.1.0, use withDomNode(?DOMNode $domNode) instead
     */
    public function withNode(?DOMNode $domNode): self
    {
        $this->domNode = $domNode;
        return $this;
    }

    public function getDomNode(): ?DOMNode
    {
        return $this->domNode;
    }

    /**
     * @deprecated since v2.1.0, use getDomNode() instead
     */
    public function getNode(): ?DOMNode
    {
        return $this->domNode;
    }
}
