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
 * Generates the opcode for a delete operation.
 */
class Delete implements OperationInterface
{
    /**
     * @var int
     */
    protected $fromLen;

    /**
     * Set the initial length.
     *
     * @param int $len Length of string.
     */
    public function __construct(int $len)
    {
        $this->fromLen = $len;
    }

    public function getFromLen(): int
    {
        return $this->fromLen;
    }

    public function getToLen(): int
    {
        return 0;
    }

    public function getOpcode(): string
    {
        if ($this->fromLen === 1) {
            return 'd';
        }
        return "d{$this->fromLen}";
    }
}
