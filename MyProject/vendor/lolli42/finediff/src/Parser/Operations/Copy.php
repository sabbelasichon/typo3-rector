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

namespace cogpowered\FineDiff\Parser\Operations;

/**
 * Generates the opcode for a copy operation.
 */
class Copy implements OperationInterface
{
    /**
     * @var int
     */
    protected $len;

    /**
     * Set the initial length.
     *
     * @param int $len Length of string.
     */
    public function __construct(int $len)
    {
        $this->len = $len;
    }

    public function getFromLen(): int
    {
        return $this->len;
    }

    public function getToLen(): int
    {
        return $this->len;
    }

    public function getOpcode(): string
    {
        if ($this->len === 1) {
            return 'c';
        }

        return "c{$this->len}";
    }

    /**
     * Increase the length of the string.
     *
     * @param int $size Amount to increase the string length by.
     * @return int New length
     */
    public function increase(int $size): int
    {
        return $this->len += $size;
    }
}
