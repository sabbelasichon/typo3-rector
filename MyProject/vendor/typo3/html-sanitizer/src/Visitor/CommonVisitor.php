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

use DOMAttr;
use DOMCdataSection;
use DOMComment;
use DOMElement;
use DOMNode;
use DOMProcessingInstruction;
use DOMText;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use TYPO3\HtmlSanitizer\Behavior;
use TYPO3\HtmlSanitizer\Context;

/**
 * Node visitor handling most common aspects for tag, attribute
 * and values as declared in provided `Behavior` instance.
 */
class CommonVisitor extends AbstractVisitor implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Behavior
     */
    protected $behavior;

    /**
     * @var Context
     */
    protected $context;

    public function __construct(Behavior $behavior)
    {
        $this->logger = new NullLogger();
        $this->behavior = $behavior;
    }

    public function beforeTraverse(Context $context): void
    {
        $this->context = $context;
        // v2.1.0: adding `#comment` and `#cdata-section` nodes for backward compatibility, will be removed with v3.0.0
        if ($this->behavior->hasNode('#comment') && $this->behavior->getNode('#comment') === null) {
            $this->behavior = $this->behavior->withNodes(new Behavior\Comment());
        }
        if ($this->behavior->hasNode('#cdata-section') && $this->behavior->getNode('#cdata-section') === null) {
            $this->behavior = $this->behavior->withNodes(new Behavior\CdataSection());
        }
    }

    public function enterNode(DOMNode $domNode): ?DOMNode
    {
        if ($domNode instanceof DOMProcessingInstruction) {
            return $this->handleInvalidNode($domNode);
        }

        if (!$domNode instanceof DOMCdataSection
            && !$domNode instanceof DOMComment
            && !$domNode instanceof DOMElement
        ) {
            return $domNode;
        }

        $node = $this->behavior->getNode($domNode->nodeName);
        if (!$node instanceof Behavior\NodeInterface) {
            return $this->handleInvalidNode($domNode);
        }

        if ($node instanceof Behavior\NodeHandler) {
            if ($node->shallHandleFirst()) {
                $domNode = $node->getHandler()->handle($node->getNode(), $domNode, $this->context, $this->behavior);
            }
            if ($node->shallProcessDefaults() && $domNode instanceof DOMElement) {
                $domNode = $this->enterDomElement($domNode, $node->getNode());
            }
            if (!$node->shallHandleFirst()) {
                $domNode = $node->getHandler()->handle($node->getNode(), $domNode, $this->context, $this->behavior);
            }
        } elseif ($node instanceof Behavior\HandlerInterface) {
            $domNode = $node->handle($node, $domNode, $this->context, $this->behavior);
            $domNode = $domNode instanceof DOMElement ? $this->enterDomElement($domNode, $node) : $domNode;
        } elseif ($domNode instanceof DOMElement) {
            $domNode = $this->enterDomElement($domNode, $node);
        }
        return $domNode;
    }

    protected function enterDomElement(?DOMNode $domNode, Behavior\NodeInterface $node): ?DOMNode
    {
        if (!$domNode instanceof DOMElement || !$node instanceof Behavior\Tag) {
            return $domNode;
        }
        $domNode = $this->processAttributes($domNode, $node);
        $domNode = $this->processChildren($domNode, $node);
        // completely remove node, in case it is expected to exist with attributes only
        if ($domNode instanceof DOMElement && $domNode->attributes->length === 0 && $node->shallPurgeWithoutAttrs()) {
            return null;
        }
        return $this->handleMandatoryAttributes($domNode, $node);
    }

    public function leaveNode(DOMNode $domNode): ?DOMNode
    {
        if (!$domNode instanceof DOMElement) {
            return $domNode;
        }
        $node = $this->behavior->getNode($domNode->nodeName);
        if ($node === null) {
            // pass custom elements, in case it has been declared
            if ($this->behavior->shallAllowCustomElements() && $this->isCustomElement($domNode)) {
                return $domNode;
            }
            // unexpected node, that should have been handled in `enterNode` already
            return null;
        }
        // completely remove node, in case it is expected to exist with children only
        if ($node instanceof Behavior\Tag
            && $node->shallPurgeWithoutChildren()
            && !$this->hasNonEmptyChildren($domNode)
        ) {
            return null;
        }
        return $domNode;
    }

    protected function processAttributes(?DOMNode $domNode, Behavior\Tag $tag): ?DOMNode
    {
        if (!$domNode instanceof DOMElement) {
            return $domNode;
        }
        // reverse processing of attributes,
        // allowing to directly remove attribute nodes
        for ($i = $domNode->attributes->length - 1; $i >= 0; $i--) {
            /** @var DOMAttr $attribute */
            $attribute = $domNode->attributes->item($i);
            try {
                $this->processAttribute($domNode, $tag, $attribute);
            } catch (Behavior\NodeException $exception) {
                return $exception->getDomNode();
            }
        }
        return $domNode;
    }

    protected function processChildren(?DOMNode $domNode, Behavior\Tag $tag): ?DOMNode
    {
        if (!$domNode instanceof DOMElement) {
            return $domNode;
        }
        if (!$tag->shallAllowChildren()
            && $domNode->childNodes->length > 0
            && $this->behavior->shallRemoveUnexpectedChildren()
        ) {
            $this->log('Found unexpected children for {nodeName}', [
                'behavior' => $this->behavior->getName(),
                'nodeType' => $domNode->nodeType,
                'nodeName' => $domNode->nodeName,
            ]);
            // reverse processing of children,
            // allowing to directly remove child nodes
            for ($i = $domNode->childNodes->length - 1; $i >= 0; $i--) {
                /** @var DOMNode $child */
                $child = $domNode->childNodes->item($i);
                $domNode->removeChild($child);
            }
        }
        return $domNode;
    }

    /**
     * @throws Behavior\NodeException
     */
    protected function processAttribute(DOMElement $domNode, Behavior\Tag $tag, DOMAttr $attribute): void
    {
        $name = strtolower($attribute->name);
        $attr = $tag->getAttr($name);
        if ($attr === null || !$attr->matchesValue($attribute->value)) {
            $this->log('Found invalid attribute {nodeName}.{attrName}', [
                'behavior' => $this->behavior->getName(),
                'nodeType' => $domNode->nodeType,
                'nodeName' => $domNode->nodeName,
                'attrName' => $attribute->nodeName,
            ]);
            $this->handleInvalidAttr($domNode, $name);
        }
    }

    protected function handleMandatoryAttributes(?DOMNode $domNode, Behavior\Tag $tag): ?DOMNode
    {
        if (!$domNode instanceof DOMElement) {
            return $domNode;
        }
        foreach ($tag->getAttrs() as $attr) {
            if ($attr->isMandatory() && !$domNode->hasAttribute($attr->getName())) {
                $this->log('Missing mandatory attribute {nodeName}.{attrName}', [
                    'behavior' => $this->behavior->getName(),
                    'nodeType' => $domNode->nodeType,
                    'nodeName' => $domNode->nodeName,
                    'attrName' => $attr->getName(),
                ]);
                return $this->handleInvalidNode($domNode);
            }
        }
        return $domNode;
    }

    protected function handleInvalidNode(DOMNode $domNode): ?DOMNode
    {
        if (
            ($domNode instanceof DOMComment && $this->behavior->shallEncodeInvalidComment())
            || ($domNode instanceof DOMCdataSection && $this->behavior->shallEncodeInvalidCdataSection())
            || ($domNode instanceof DOMProcessingInstruction && $this->behavior->shallEncodeInvalidProcessingInstruction())
        ) {
            $this->log('Found unexpected node {nodeName}', [
                'behavior' => $this->behavior->getName(),
                'nodeType' => $domNode->nodeType,
                'nodeName' => $domNode->nodeName,
            ]);
            return $this->convertToText($domNode);
        }
        if ($domNode instanceof DOMElement) {
            // pass custom elements, in case it has been declared
            if ($this->behavior->shallAllowCustomElements() && $this->isCustomElement($domNode)) {
                $this->log('Allowed custom element {nodeName}', [
                    'behavior' => $this->behavior->getName(),
                    'nodeType' => $domNode->nodeType,
                    'nodeName' => $domNode->nodeName,
                ]);
                return $domNode;
            }
            $this->log('Found unexpected tag {nodeName}', [
                'behavior' => $this->behavior->getName(),
                'nodeType' => $domNode->nodeType,
                'nodeName' => $domNode->nodeName,
            ]);
            if ($this->behavior->shallEncodeInvalidTag()) {
                return $this->convertToText($domNode);
            }
        }
        $this->log('Removed unexpected node {nodeName}', [
            'behavior' => $this->behavior->getName(),
            'nodeType' => $domNode->nodeType,
            'nodeName' => $domNode->nodeName,
        ]);
        return null;
    }

    /**
     * @throws Behavior\NodeException
     */
    protected function handleInvalidAttr(DOMNode $domNode, string $name): void
    {
        if ($this->behavior->shallEncodeInvalidAttr()) {
            throw Behavior\NodeException::create()->withDomNode($this->convertToText($domNode));
        }
        if (!$domNode instanceof DOMElement) {
            throw Behavior\NodeException::create()->withDomNode(null);
        }
        $domNode->removeAttribute($name);
    }

    /**
     * Converts node/element to text node, basically disarming tags.
     * (`<script>` --> `&lt;script&gt;` when DOM is serialized as string)
     */
    protected function convertToText(DOMNode $domNode): DOMText
    {
        $text = new DOMText();
        $text->nodeValue = $this->context->parser->saveHTML($domNode);
        return $text;
    }

    /**
     * Determines whether a node has children. This is a special
     * handling for nodes that only allow text nodes that still can be empty.
     *
     * For instance `<script></script>` is considered empty,
     * albeit `$domNode->childNodes->length === 1`.
     */
    protected function hasNonEmptyChildren(DOMNode $domNode): bool
    {
        if ($domNode->childNodes->length === 0) {
            return false;
        }
        for ($i = $domNode->childNodes->length - 1; $i >= 0; $i--) {
            $child = $domNode->childNodes->item($i);
            if (!$child instanceof DOMText
                || trim($child->textContent) !== ''
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Whether given node name can be considered as custom element.
     * (see https://html.spec.whatwg.org/multipage/custom-elements.html#valid-custom-element-name)
     */
    protected function isCustomElement(DOMNode $domNode): bool
    {
        return $domNode instanceof DOMElement
            && preg_match('#^[a-z][a-z0-9]*-.+#', $domNode->nodeName) > 0;
    }

    protected function log(string $message, array $context = [], $level = null): void
    {
        // @todo consider given minimum log-level
        if (!isset($context['initiator'])) {
            $context['initiator'] = (string)$this->context->initiator;
        }
        $this->logger->debug($message, $context);
    }
}
