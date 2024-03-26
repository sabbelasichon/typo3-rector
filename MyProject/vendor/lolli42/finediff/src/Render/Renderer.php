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

abstract class Renderer implements RendererInterface
{
    /**
     * Covert text based on the provided opcodes.
     *
     * @param string $from_text
     * @param string|OpcodesInterface|mixed $opcodes Throws on non-string and non-OpcodesInterface
     * @return string
     */
    public function process(string $from_text, $opcodes)
    {
        // Validate opcodes
        if (!is_string($opcodes) && !($opcodes instanceof OpcodesInterface)) {
            throw new \InvalidArgumentException('Invalid opcodes type');
        }
        $opcodes = ($opcodes instanceof OpcodesInterface) ? $opcodes->generate() : $opcodes;

        // Holds the generated string that is returned
        $output = '';

        $opcodes_len    = mb_strlen($opcodes);
        $from_offset    = 0;
        $opcodes_offset = 0;

        while ($opcodes_offset < $opcodes_len) {
            $opcode = mb_substr($opcodes, $opcodes_offset, 1);
            $opcodes_offset++;
            $n = (int)(mb_substr($opcodes, $opcodes_offset));

            if ($n) {
                $opcodes_offset += mb_strlen((string)$n);
            } else {
                $n = 1;
            }

            if ($opcode === 'c') {
                // copy n characters from source
                $data = $this->callback('c', $from_text, $from_offset, $n);
                $from_offset += $n;
            } elseif ($opcode === 'd') {
                // delete n characters from source
                $data = $this->callback('d', $from_text, $from_offset, $n);
                $from_offset += $n;
            } else /* if ( $opcode === 'i' ) */ {
                // insert n characters from opcodes
                $data = $this->callback('i', $opcodes, $opcodes_offset + 1, $n);
                $opcodes_offset += 1 + $n;
            }

            $output .= $data;
        }

        return $output;
    }
}
