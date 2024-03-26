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

use Masterminds\HTML5;

/**
 * Context container shared between sanitizer and visitor instances
 * (currently does not contain much, but might(!) be more in the future)
 */
class Context
{
    /**
     * @var HTML5
     */
    public $parser;

    /**
     * @var ?InitiatorInterface
     */
    public $initiator;

    public function __construct(HTML5 $parser, InitiatorInterface $initiator = null)
    {
        $this->parser = $parser;
        $this->initiator = $initiator;
    }
}
