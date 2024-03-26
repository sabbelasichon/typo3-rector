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

use LogicException;

/**
 * Model of tag
 */
class Tag implements NodeInterface
{
    /**
     * not having any behavioral capabilities
     */
    public const BLUNT = 0;

    /**
     * whether to purge this tag in case it does not have any attributes
     */
    public const PURGE_WITHOUT_ATTRS = 1;

    /**
     * whether to purge this tag in case it does not have children
     */
    public const PURGE_WITHOUT_CHILDREN = 2;

    /**
     * whether this tag allows to have children
     */
    public const ALLOW_CHILDREN = 8;

    /**
     * whether this tag is allowed to be serialized as raw text (e.g. for `<script>` elements)
     * see https://html.spec.whatwg.org/multipage/syntax.html#raw-text-elements
     */
    public const ALLOW_INSECURE_RAW_TEXT = 16;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $flags = 0;

    /**
     * @var array<string, Attr>
     */
    protected $attrs = [];

    public function __construct(string $name, int $flags = null)
    {
        $this->name = $name;
        // using `null` as default - potentially allows switching
        // the real default value from `BLUNT` to e.g. `ALLOW_CHILDREN`
        $this->flags = $flags ?? self::BLUNT;

        if ($this->shallPurgeWithoutChildren() && !$this->shallAllowChildren()) {
            throw new LogicException(
                sprintf('Tag %s does not allow children, but shall be purged without them', $name),
                1625397681
            );
        }
    }

    public function addAttrs(Attr ...$attrs): self
    {
        $names = array_map([$this, 'getAttrName'], $attrs);
        $this->assertScalarUniqueness($names);
        $indexedAttrs = array_combine($names, $attrs);
        if (!is_array($indexedAttrs)) {
            return $this;
        }
        $this->assertAttrUniqueness($indexedAttrs);
        $this->attrs = array_merge($this->attrs, $indexedAttrs);
        return $this;
    }

    /**
     * @return Attr[]
     */
    public function getAttrs(): array
    {
        return $this->attrs;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function shallPurgeWithoutAttrs(): bool
    {
        return ($this->flags & self::PURGE_WITHOUT_ATTRS) === self::PURGE_WITHOUT_ATTRS;
    }

    public function shallPurgeWithoutChildren(): bool
    {
        return ($this->flags & self::PURGE_WITHOUT_CHILDREN) === self::PURGE_WITHOUT_CHILDREN;
    }

    public function shallAllowChildren(): bool
    {
        return ($this->flags & self::ALLOW_CHILDREN) === self::ALLOW_CHILDREN;
    }

    public function shallAllowInsecureRawText(): bool
    {
        return ($this->flags & self::ALLOW_INSECURE_RAW_TEXT) === self::ALLOW_INSECURE_RAW_TEXT;
    }

    public function getAttr(string $name): ?Attr
    {
        $name = strtolower($name);
        if (isset($this->attrs[$name])) {
            return $this->attrs[$name];
        }
        foreach ($this->attrs as $attr) {
            if ($attr->matchesName($name)) {
                return $attr;
            }
        }
        return null;
    }

    /**
     * @param string[] $names
     * @throws LogicException
     */
    protected function assertScalarUniqueness(array $names): void
    {
        $ambiguousNames = array_diff_assoc($names, array_unique($names));
        if ($ambiguousNames !== []) {
            throw new LogicException(
                sprintf(
                    'Ambiguous attr names %s.',
                    implode(', ', $ambiguousNames)
                ),
                1625590355
            );
        }
    }

    /**
     * @param array<string, Attr> $attrs
     * @throws LogicException
     */
    protected function assertAttrUniqueness(array $attrs): void
    {
        $existingAttrNames = [];
        $currentAttrNames = array_keys($this->attrs);
        foreach ($attrs as $attr) {
            $currentAttr = $this->getAttr($attr->getName());
            // finds exact matches, and new static attrs that already have existing prefixed attrs
            if ($currentAttr !== null) {
                $existingAttrNames[] = $attr->getName();
            // finds new prefixed attrs that would be ambiguous for existing attrs
            } elseif ($attr->isPrefix()) {
                foreach ($currentAttrNames as $currentAttrName) {
                    if ($attr->matchesName($currentAttrName)) {
                        $existingAttrNames[] = $attr->getName();
                        break;
                    }
                }
            }
        }
        $existingAttrNames = array_filter($existingAttrNames);
        if ($existingAttrNames !== []) {
            throw new LogicException(
                sprintf(
                    'Cannot redeclare attr names %s.',
                    implode(', ', $existingAttrNames)
                ),
                1625394715
            );
        }
    }

    protected function getAttrName(Attr $attr): string
    {
        return strtolower($attr->getName());
    }
}
