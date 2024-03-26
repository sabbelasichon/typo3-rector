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

namespace TYPO3\CMS\Fluid\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

/**
 * Wrapper for PHPs :php:`nl2br` function.
 * See https://www.php.net/manual/function.nl2br.php.
 *
 * Examples
 * ========
 *
 * Default
 * -------
 *
 * ::
 *
 *    <f:format.nl2br>{text_with_linebreaks}</f:format.nl2br>
 *
 * Text with line breaks replaced by ``<br />``
 *
 * Inline notation
 * ---------------
 *
 * ::
 *
 *    {text_with_linebreaks -> f:format.nl2br()}
 *
 * Text with line breaks replaced by ``<br />``
 */
final class Nl2brViewHelper extends AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('value', 'string', 'string to format');
    }

    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext): string
    {
        return nl2br((string)$renderChildrenClosure());
    }

    /**
     * Explicitly set argument name to be used as content.
     */
    public function resolveContentArgumentName(): string
    {
        return 'value';
    }
}
