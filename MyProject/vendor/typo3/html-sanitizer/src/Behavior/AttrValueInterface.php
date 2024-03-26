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
 * Interface of tag attribute values & verification, e.g.
 * whether it's a valid local URL, mail address, integer, ...
 */
interface AttrValueInterface
{
    public function matches(string $value): bool;
}
