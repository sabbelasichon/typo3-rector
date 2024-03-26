<?php

declare(strict_types=1);

/*
 * FINE granularity DIFF
 *
 * (c) 2011 Raymond Hill (http://raymondhill.net/blog/?p=441)
 * (c) 2013 Robert Crowe (http://cogpowered.com)
 * (c) 2021 Christian Kuhn
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace cogpowered\FineDiff\Render;

use cogpowered\FineDiff\Parser\OpcodesInterface;

interface RendererInterface
{
    /**
     * @param string $from_text
     * @param string|OpcodesInterface|mixed $opcode Throws on non-string and non-OpcodesInterface
     * @return string
     */
    public function process(string $from_text, $opcode);

    public function callback(string $opcode, string $from, int $from_offset, int $from_len): string;
}
