<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\CMS\Core\Utility;

use cogpowered\FineDiff\Diff;
use cogpowered\FineDiff\Granularity\Character;
use cogpowered\FineDiff\Granularity\Word;

/**
 * This class has functions which generates a difference output of a content string
 */
class DiffUtility
{
    /**
     * If set, the HTML tags are stripped from the input strings first.
     */
    public bool $stripTags = true;

    /**
     * Returns a color-marked-up diff output in HTML from the input strings.
     */
    public function makeDiffDisplay(string $str1, string $str2, DiffGranularity $granularity = DiffGranularity::WORD): string
    {
        if ($this->stripTags) {
            $str1 = strip_tags($str1);
            $str2 = strip_tags($str2);
        }
        $granularity = $granularity === DiffGranularity::WORD ? new Word() : new Character();
        $diff = new Diff($granularity);
        return $diff->render($str1, $str2);
    }
}
