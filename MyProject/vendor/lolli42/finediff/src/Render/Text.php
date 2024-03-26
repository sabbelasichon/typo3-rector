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

class Text extends Renderer
{
    public function callback(string $opcode, string $from, int $from_offset, int $from_len): string
    {
        if ($opcode === 'c' || $opcode === 'i') {
            return mb_substr($from, $from_offset, $from_len);
        }
        return '';
    }
}
