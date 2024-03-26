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

namespace TYPO3\HtmlSanitizer;

use DOMDocumentFragment;
use DOMNode;
use DOMNodeList;
use Masterminds\HTML5;
use TYPO3\HtmlSanitizer\Serializer\Rules;
use TYPO3\HtmlSanitizer\Serializer\RulesInterface;
use TYPO3\HtmlSanitizer\Visitor\VisitorInterface;

/**
 * HTML Sanitizer in a nutshell:
 *
 * + `Behavior` contains declarative settings for a particular process for sanitizing HTML.
 * + `Visitor` (multiple different visitors can exist at the same time) are actually doing the
 *   work based on the declared `Behavior`. Visitors can modify nodes or mark them for deletion.
 * + `Sanitizer` can be considered as the working instance, invoking visitors, parsing and
 *   serializing HTML. In general this instance does not contain much logic on how to handle
 *   particular nodes, attributes or values
 *
 * This `Sanitizer` class is agnostic specific configuration - it's purpose is to parse HTML,
 * invoke all registered visitors (they actually do the work and contain specific logic) and
 * finally provide HTML serialization as string again.
 */
class Sanitizer
{
    protected const mastermindsDefaultOptions = [
        // Whether the serializer should aggressively encode all characters as entities.
        'encode_entities' => false,
        'encode_attributes' => true,
        // Prevents the parser from automatically assigning the HTML5 namespace to the DOM document.
        // (adjusted due to https://github.com/Masterminds/html5-php/issues/181#issuecomment-643767471)
        'disable_html_ns' => true,
    ];

    /**
     * @var VisitorInterface[]
     */
    protected $visitors = [];

    /**
     * @var ?Behavior
     */
    protected $behavior = null;

    /**
     * @var HTML5
     */
    protected $parser;

    /**
     * @var DOMDocumentFragment
     * @deprecated since v2.1.0, not required anymore
     */
    protected $root;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @param Behavior|VisitorInterface ...$items
     *
     * @todo use `__construct(Behavior $behavior, VisitorInterface ...$visitors)`
     * (which would have been a breaking change with a PHP fatal error)
     */
    public function __construct(...$items)
    {
        $this->visitors = [];
        foreach ($items as $item) {
            if ($item instanceof VisitorInterface) {
                $this->visitors[] = $item;
            } elseif ($item instanceof Behavior && $this->behavior === null) {
                $this->behavior = $item;
            }
        }
        $this->parser = $this->createParser();
    }

    public function sanitize(string $html, InitiatorInterface $initiator = null): string
    {
        $root = $this->parse($html);
        // @todo drop deprecated property
        $this->root = $root;
        $this->handle($root, $initiator);
        $rules = $this->createRules($initiator);
        $serialized = $this->serialize($root, $rules);
        $this->closeRulesStream($rules);
        return $serialized;
    }

    protected function parse(string $html): DOMDocumentFragment
    {
        return $this->parser->parseFragment($html);
    }

    protected function handle(DOMNode $domNode, InitiatorInterface $initiator = null): DOMNode
    {
        $this->context = new Context($this->parser, $initiator);
        $this->beforeTraverse();
        $this->traverseNodeList($domNode->childNodes);
        $this->afterTraverse();
        return $domNode;
    }

    /**
     * Custom implementation of `\Masterminds\HTML5::save` and `\Masterminds\HTML5::saveHTML`.
     */
    protected function serialize(DOMNode $domNode, RulesInterface $rules): string
    {
        $rules->traverse($domNode);
        return stream_get_contents($rules->getStream(), -1, 0);
    }

    protected function beforeTraverse(): void
    {
        foreach ($this->visitors as $visitor) {
            $visitor->beforeTraverse($this->context);
        }
    }

    protected function traverse(DOMNode $domNode): void
    {
        foreach ($this->visitors as $visitor) {
            $result = $visitor->enterNode($domNode);
            $domNode = $this->replaceNode($domNode, $result);
            if ($domNode === null) {
                return;
            }
        }

        if ($domNode->hasChildNodes()) {
            $this->traverseNodeList($domNode->childNodes);
        }

        foreach ($this->visitors as $visitor) {
            $result = $visitor->leaveNode($domNode);
            $domNode = $this->replaceNode($domNode, $result);
            if ($domNode === null) {
                return;
            }
        }
    }

    /**
     * Traverses node-list (child-nodes) in reverse(!) order to allow
     * directly removing child nodes, keeping node-list indexes.
     *
     * @param DOMNodeList $domNodeList
     */
    protected function traverseNodeList(DOMNodeList $domNodeList): void
    {
        for ($i = $domNodeList->length - 1; $i >= 0; $i--) {
            /** @var DOMNode $item */
            $item = $domNodeList->item($i);
            $this->traverse($item);
        }
    }

    protected function afterTraverse(): void
    {
        foreach ($this->visitors as $visitor) {
            $visitor->afterTraverse($this->context);
        }
    }

    protected function replaceNode(DOMNode $source, ?DOMNode $target): ?DOMNode
    {
        if ($target === null) {
            $source->parentNode->removeChild($source);
        } elseif ($source !== $target) {
            if ($source->ownerDocument !== $target->ownerDocument
                && $source->ownerDocument !== null
                && $target->ownerDocument !== null
            ) {
                $target = $source->ownerDocument->importNode($target, true);
            }
            $source->parentNode->replaceChild($target, $source);
        }
        return $target;
    }

    protected function createRules(InitiatorInterface $initiator = null): Rules
    {
        $stream = fopen('php://temp', 'wb');
        return (new Rules($stream, self::mastermindsDefaultOptions))
            ->withBehavior($this->behavior ?? new Behavior())
            ->withInitiator($initiator);
    }

    protected function closeRulesStream(RulesInterface $rules): bool
    {
        return fclose($rules->getStream());
    }

    protected function createParser(): HTML5
    {
        return new HTML5(self::mastermindsDefaultOptions);
    }
}
