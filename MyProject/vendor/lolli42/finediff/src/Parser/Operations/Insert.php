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
class Insert implements OperationInterface
{
    /**
     * @var string
     */
    protected $text;

    /**
     * Sets the text that the operation is working with.
     */
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function getFromLen(): int
    {
        return 0;
    }

    public function getToLen(): int
    {
        return mb_strlen($this->text);
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getOpcode(): string
    {
        $to_len = mb_strlen($this->text);
        if ($to_len === 1) {
            return "i:{$this->text}";
        }
        return "i{$to_len}:{$this->text}";
    }
}
