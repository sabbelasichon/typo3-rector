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

/**
 * Model of tag attribute
 */
class Attr
{
    /**
     * not having any behavioral capabilities
     */
    public const BLUNT = 0;

    /**
     * whether given name shall be considered as prefix, e.g.
     * `data-` or `aria-` for multiple similar and safe attribute names
     */
    public const NAME_PREFIX = 1;

    /**
     * whether the first match in `$values` shall be considered
     * as indicator the attribute value is valid in general - if
     * this flag is not given, all declared `$values` must match
     *
     * @deprecated since version 2.0.13 (it is the default behavior now)
     */
    public const MATCH_FIRST_VALUE = 2;

    /**
     * whether all `$values` shall be considered as indicator an
     * attribute value is valid - if this flag is not given, the
     * first match in `$values` is taken
     */
    public const MATCH_ALL_VALUES = 4;

    /**
     * whether the current attribute is mandatory for the tag
     */
    public const MANDATORY = 8;

    /**
     * either specific attribute name (`class`) or a prefix
     * (`data-`) in case corresponding NAME_PREFIX flag is set
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $flags = 0;

    /**
     * @var AttrValueInterface[]
     */
    protected $values = [];

    public function __construct(string $name, int $flags = 0)
    {
        $this->name = $name;
        $this->flags = $flags;
    }

    public function withFlags(int $flags): self
    {
        if ($flags === $this->flags) {
            return $this;
        }
        $target = clone $this;
        $target->flags = $flags;
        return $target;
    }

    /**
     * Adds value items directly to the current `Attr` instance.
     *
     * @param AttrValueInterface ...$values
     * @return $this
     */
    public function addValues(AttrValueInterface ...$values): self
    {
        $this->values = array_merge($this->values, $values);
        return $this;
    }

    /**
     * Clones current `Attr` instance, then adds value items to that cloned instance.
     *
     * @param AttrValueInterface ...$values
     * @return $this
     */
    public function withValues(AttrValueInterface ...$values): self
    {
        $differences = array_udiff($values, $this->values, [$this, 'isDifferentValue']);
        if (empty($differences)) {
            return $this;
        }
        $target = clone $this;
        $target->values = array_merge($target->values, $values);
        return $target;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFlags(): int
    {
        return $this->flags;
    }

    /**
     * @return AttrValueInterface[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    public function isPrefix(): bool
    {
        return ($this->flags & self::NAME_PREFIX) === self::NAME_PREFIX;
    }

    /**
     * @deprecated since version 2.0.13 (it is the default behavior now)
     */
    public function shallMatchFirstValue(): bool
    {
        return ($this->flags & self::MATCH_FIRST_VALUE) === self::MATCH_FIRST_VALUE;
    }

    public function shallMatchAllValues(): bool
    {
        return ($this->flags & self::MATCH_ALL_VALUES) === self::MATCH_ALL_VALUES;
    }

    public function isMandatory(): bool
    {
        return ($this->flags & self::MANDATORY) === self::MANDATORY;
    }

    public function matchesName(string $givenName): bool
    {
        $givenName = strtolower($givenName);
        return $givenName === $this->name
            || $this->isPrefix() && strpos($givenName, $this->name) === 0;
    }

    public function matchesValue(string $givenValue): bool
    {
        // no declared values, means `true` as well
        if ($this->values === []) {
            return true;
        }
        $matchAllValues = $this->shallMatchAllValues();
        foreach ($this->values as $value) {
            // + result: false, matchAllValues: true --> return false
            // + result: true, matchAllValues: false --> return true
            // (anything else continues processing)
            $result = $value->matches($givenValue);
            if ($result !== $matchAllValues) {
                return !$matchAllValues;
            }
        }
        // + matchAllValues: true --> return true (since no other match failed before)
        // + matchAllValues: false --> return false (since no other match succeeded before)
        return $matchAllValues;
    }

    protected function isDifferentValue(AttrValueInterface $a, AttrValueInterface $b): int
    {
        return (int)($a !== $b);
    }
}
