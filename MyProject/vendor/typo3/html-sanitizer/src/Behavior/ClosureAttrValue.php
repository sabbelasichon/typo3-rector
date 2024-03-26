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

use Closure;
use LogicException;

class ClosureAttrValue implements AttrValueInterface
{
    /**
     * @var Closure
     */
    protected $closure;

    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    public function matches(string $value): bool
    {
        $result = call_user_func($this->closure, $value);
        if (!is_bool($result)) {
            throw new LogicException('Closure must return boolean value', 1624908450);
        }
        return $result;
    }
}
