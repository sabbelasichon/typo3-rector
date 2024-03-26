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
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Formats a number with custom precision, decimal point and grouped thousands.
 * See https://www.php.net/manual/function.number-format.php.
 *
 * Examples
 * ========
 *
 * Defaults
 * --------
 *
 * ::
 *
 *    <f:format.number>423423.234</f:format.number>
 *
 * ``423,423.20``
 *
 * With all parameters
 * -------------------
 *
 * ::
 *
 *    <f:format.number decimals="1" decimalSeparator="," thousandsSeparator=".">
 *        423423.234
 *    </f:format.number>
 *
 * ``423.423,2``
 */
final class NumberViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * Output is escaped already. We must not escape children, to avoid double encoding.
     *
     * @var bool
     */
    protected $escapeChildren = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('decimals', 'int', 'The number of digits after the decimal point', false, 2);
        $this->registerArgument('decimalSeparator', 'string', 'The decimal point character', false, '.');
        $this->registerArgument('thousandsSeparator', 'string', 'The character for grouping the thousand digits', false, ',');
    }

    /**
     * Format the numeric value as a number with grouped thousands, decimal point and precision.
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext): string
    {
        $decimals = (int)$arguments['decimals'];
        $decimalSeparator = $arguments['decimalSeparator'];
        $thousandsSeparator = $arguments['thousandsSeparator'];
        $stringToFormat = $renderChildrenClosure();
        return number_format((float)$stringToFormat, $decimals, $decimalSeparator, $thousandsSeparator);
    }
}
