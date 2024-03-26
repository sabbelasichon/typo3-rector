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

namespace cogpowered\FineDiff\Parser;

use cogpowered\FineDiff\Parser\Operations\OperationInterface;

interface OpcodesInterface
{
    /**
     * Get the opcodes.
     *
     * @return array<int, string>
     */
    public function getOpcodes(): array;

    /**
     * Set the opcodes for this parse.
     *
     * @param array<int, OperationInterface> $opcodes Elements must be an instance of OperationInterface.
     */
    public function setOpcodes(array $opcodes): void;

    /**
     * Return the opcodes in a format that can then be rendered.
     */
    public function generate(): string;

    /**
     * When object is cast to a string returns opcodes as string.
     */
    public function __toString();
}
