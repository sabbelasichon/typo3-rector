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

namespace TYPO3\HtmlSanitizer\Behavior\Attr;

use TYPO3\HtmlSanitizer\Behavior\AttrValueInterface;
use TYPO3\HtmlSanitizer\Behavior\RegExpAttrValue;

/**
 * Builder for Uri attributes.
 */
class UriAttrValueBuilder
{
    /**
     * @var bool
     */
    protected $allowLocal = false;

    /**
     * @var bool
     */
    protected $allowSchemeLess = false;

    /**
     * @var string[]
     */
    protected $allowSchemes = [];

    /**
     * @var string[]
     */
    protected $allowDataMediaTypes = [];

    public function allowLocal(bool $allowLocal): self
    {
        $this->allowLocal = $allowLocal;
        return $this;
    }

    public function allowSchemeLess(bool $schemeLess): self
    {
        $this->allowSchemeLess = $schemeLess;
        return $this;
    }

    public function allowSchemes(string ...$allowSchemes): self
    {
        $differences = array_diff_assoc($allowSchemes, array_unique($this->allowSchemes));
        $this->allowSchemes = array_merge($this->allowSchemes, $differences);
        return $this;
    }

    public function allowDataMediaTypes(string ...$allowDataMediaTypes): self
    {
        $differences = array_diff_assoc($allowDataMediaTypes, array_unique($this->allowDataMediaTypes));
        $this->allowDataMediaTypes = array_merge($this->allowDataMediaTypes, $differences);
        return $this;
    }

    /**
     * @return AttrValueInterface[]
     */
    public function getValues(): array
    {
        $values = [];
        if ($this->allowLocal) {
            // + starting with `/` but, not starting with `//`
            // + not starting with `/` and not having `:` at all
            $values[] = new RegExpAttrValue('#^(/(?!/)|[^/:][^:]*$)#');
        }
        if ($this->allowSchemeLess) {
            // + starting with `//` followed by any character
            $values[] = new RegExpAttrValue('#^//.#');
        }
        if ($this->allowSchemes !== []) {
            $values[] = new RegExpAttrValue(sprintf(
                '#^(%s):#i',
                implode('|', array_map([$this, 'pregQuote'], $this->allowSchemes))
            ));
        }
        if ($this->allowDataMediaTypes !== []) {
            $values[] = new RegExpAttrValue(sprintf(
                // example: `data:image/[^;,]+(?:;(?:base64)?)?,`
                '#^data:(?:%s)/[^;,]+(?:;(?:base64)?)?,#',
                implode('|', array_map([$this, 'pregQuote'], $this->allowDataMediaTypes))
            ));
        }
        return $values;
    }

    protected function pregQuote(string $value): string
    {
        return preg_quote($value, '#');
    }
}
