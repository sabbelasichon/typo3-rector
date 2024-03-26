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

namespace cogpowered\FineDiff\Granularity;

/**
 * Granularities should extend this class.
 */
abstract class Granularity implements GranularityInterface
{
    /**
     * @var array<int, array<int, string>> Extending granularities should override this.
     */
    protected $delimiters = [];

    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->delimiters[$offset]);
    }

    /**
     * @return array<int, string>|null
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->delimiters[$offset] ?? null;
    }

    /**
     * @param mixed $value
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->delimiters[] = $value;
        } else {
            $this->delimiters[$offset] = $value;
        }
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->delimiters[$offset]);
    }

    /**
     * Return the number of delimiters this granularity contains.
     *
     * @return int
     */
    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->delimiters);
    }

    public function getDelimiters(): array
    {
        return $this->delimiters;
    }

    public function setDelimiters(array $delimiters): void
    {
        $this->delimiters = $delimiters;
    }
}
