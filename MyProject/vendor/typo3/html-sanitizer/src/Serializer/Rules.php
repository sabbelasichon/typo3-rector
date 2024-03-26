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

namespace TYPO3\HtmlSanitizer\Serializer;

use DOMCharacterData;
use DOMElement;
use DOMNode;
use Masterminds\HTML5\Elements;
use Masterminds\HTML5\Serializer\OutputRules;
use Masterminds\HTML5\Serializer\Traverser;
use TYPO3\HtmlSanitizer\Behavior;
use TYPO3\HtmlSanitizer\InitiatorInterface;

class Rules extends OutputRules implements RulesInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var ?Traverser
     */
    protected $traverser;

    /**
     * @var ?Behavior
     */
    protected $behavior;

    /**
     * @var ?InitiatorInterface
     */
    protected $initiator;

    /**
     * @var bool
     */
    protected $encodeAttributes;

    /**
     * @param Behavior $behavior
     * @param resource$output
     * @param array $options
     * @return self
     */
    public static function create(Behavior $behavior, $output, array $options = []): self
    {
        $target = new self($output, $options);
        $target->options = $options;
        $target->behavior = $behavior;
        return $target;
    }

    /**
     * @param resource $output
     * @param array $options
     */
    public function __construct($output, $options = [])
    {
        $this->options = (array)$options;
        $this->encodeAttributes = !empty($options['encode_attributes']);
        parent::__construct($output, $this->options);
    }

    public function withBehavior(Behavior $behavior): self
    {
        if ($this->behavior === $behavior) {
            return $this;
        }
        $target = clone $this;
        $target->behavior = $behavior;
        return $target;
    }

    public function withInitiator(?InitiatorInterface $initiator): self
    {
        if ($this->initiator === $initiator) {
            return $this;
        }
        $target = clone $this;
        $target->initiator = $initiator;
        return $target;
    }

    public function traverse(DOMNode $domNode): void
    {
        $traverser = new Traverser($domNode, $this->out, $this, $this->options);
        $traverser->walk();
        // release the traverser to avoid cyclic references and allow PHP
        // to free memory without waiting for gc_collect_cycles
        $this->unsetTraverser();
    }

    /**
     * @return resource
     */
    public function getStream()
    {
        return $this->out;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function element($domNode): void
    {
        if (!$domNode instanceof DOMElement) {
            return;
        }
        // process non-raw-text elements and `<svg>` or `<math>` elements as usual
        if (!$this->isRawText($domNode)
            || in_array($this->resolveNodeName($domNode), ['svg', 'math'], true)
        ) {
            parent::element($domNode);
            return;
        }

        $this->openTag($domNode);
        if ($this->shallAllowInsecureRawText($domNode)) {
            // the potentially insecure case, not encoding node data
            foreach ($domNode->childNodes as $child) {
                if ($child instanceof DOMCharacterData) {
                    $this->wr($child->data);
                } elseif ($child instanceof DOMElement) {
                    $this->element($child);
                }
            }
        } elseif ($domNode->hasChildNodes()) {
            // enforce encoding for those raw text elements (different to original implementation)
            $this->traverser->children($domNode->childNodes);
        }
        if (!$this->isVoid($domNode)) {
            $this->closeTag($domNode);
        }
    }

    public function text($domNode): void
    {
        if (!$domNode instanceof DOMNode) {
            return;
        }
        // @todo if allowed as text raw element
        $parentDomNode = $domNode->parentNode ?? null;
        if (!$this->isRawText($parentDomNode) || !$this->shallAllowInsecureRawText($parentDomNode)) {
            $this->wr($this->enc($domNode->data));
            return;
        }
        // the potentially insecure case, not encoding node data
        $this->wr($domNode->data);
    }

    protected function enc($text, $attribute = false): string
    {
        if ($attribute && $this->encodeAttributes && !$this->encode) {
            // In contrast to parent::enc() (when $this->encode is true),
            // we are using htmlspecialchars() instead of htmlentities() as
            // colons and slashes do not need to be aggressively escaped.
            $value = htmlspecialchars(
                $text,
                ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES,
                'UTF-8',
                // $double_encode: true
                // (name is misleading, it actually means: disable-automagic/always-encode)
                // Our input is always entity decoded by the parser and we do not
                // want to consider our input to possibly contain valid entities
                // we rather want to treat it as pure text, that is *always* to be encoded.
                true
            );
            return $value;
        }
        return parent::enc($text, $attribute);
    }

    /**
     * If the element has a declared namespace in the HTML, MathML or
     * SVG namespaces, we use the localName instead of the tagName.
     */
    protected function resolveNodeName(DOMElement $domNode): string
    {
        return $this->traverser->isLocalElement($domNode) ? $domNode->localName : $domNode->tagName;
    }

    protected function shallAllowInsecureRawText(?DOMNode $domNode): bool
    {
        if (!$domNode instanceof DOMNode || !$this->behavior instanceof Behavior) {
            return false;
        }
        $tag = $this->behavior->getTag($domNode->nodeName);
        return $tag instanceof Behavior\Tag && $tag->shallAllowInsecureRawText();
    }

    protected function isRawText(?DOMNode $domNode): bool
    {
        return $domNode instanceof DOMNode
            && !empty($domNode->tagName)
            && Elements::isA($domNode->localName, Elements::TEXT_RAW);
    }

    protected function isVoid(?DOMNode $domNode): bool
    {
        return $domNode instanceof DOMNode
            && !empty($domNode->tagName)
            && Elements::isA($domNode->localName, Elements::VOID_TAG);
    }
}
