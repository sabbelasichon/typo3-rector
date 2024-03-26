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

class MultiTokenAttrValue implements AttrValueInterface
{
    /**
     * @var string
     */
    protected $delimiter;

    /**
     * @var list<string>
     */
    protected $tokens;

    public function __construct(string $delimiter, string ...$tokens)
    {
        if ($delimiter === '') {
            throw new LogicException('Delimiter cannot be empty', 1642111976);
        }
        $tokens = array_filter($tokens, [$this, 'keepNonEmpty']);
        if ($tokens === []) {
            throw new LogicException('Tokens cannot be empty or only empty strings', 1642111637);
        }
        $this->delimiter = $delimiter;
        $this->tokens = $tokens;
    }

    public function matches(string $value): bool
    {
        $tokens = explode($this->delimiter, $value);
        $tokens = array_filter($tokens, [$this, 'keepNonEmpty']);
        // in case there is no token, the result implicitly is `false`
        if (empty($tokens)) {
            return false;
        }
        return array_diff($tokens, $this->tokens) === [];
    }

    protected function keepNonEmpty(string $item): bool
    {
        return $item !== '';
    }
}
