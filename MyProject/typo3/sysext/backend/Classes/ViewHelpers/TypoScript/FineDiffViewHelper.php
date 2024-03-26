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

namespace TYPO3\CMS\Backend\ViewHelpers\TypoScript;

use cogpowered\FineDiff\Diff;
use cogpowered\FineDiff\Granularity\Word;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Runs two strings through 'FineDiff' on word level.
 *
 * @internal This experimental ViewHelper is not part of TYPO3 Core API and may change or vanish any time.
 */
final class FineDiffViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('from', 'string', 'Source string', true, '');
        $this->registerArgument('to', 'string', 'Target string', true, '');
    }

    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext): string
    {
        $diff = new Diff(new Word());
        return $diff->render($arguments['from'], $arguments['to']);
    }
}
