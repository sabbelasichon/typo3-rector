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

namespace cogpowered\FineDiff;

/**
 * Used by classes implementing cogpowered\FineDiff\Granularity\GranularityInterface.
 *
 * Class is used more like an Enum type; the class can not be instantiated.
 */
abstract class Delimiters
{
    public const PARAGRAPH = ["\n", "\r"];
    public const SENTENCE = ['.', "\n", "\r"];
    public const WORD = [' ', "\t", '.', "\n", "\r"];
    public const CHARACTER = [''];

    /**
     * Do not allow this class to be instantiated.
     */
    private function __construct()
    {
    }
}
