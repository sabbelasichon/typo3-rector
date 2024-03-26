<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

/**
 * Outputs an argument/value without any escaping and wraps it with CDATA tags.
 *
 * PAY SPECIAL ATTENTION TO SECURITY HERE (especially Cross Site Scripting),
 * as the output is NOT SANITIZED!
 *
 * Examples
 * ========
 *
 * Child nodes
 * -----------
 *
 * ::
 *
 *     <f:format.cdata>{string}</f:format.cdata>
 *
 * Output::
 *
 *     <![CDATA[(Content of {string} without any conversion/escaping)]]>
 *
 * Value attribute
 * ---------------
 *
 * ::
 *
 *     <f:format.cdata value="{string}" />
 *
 * Output::
 *
 *     <![CDATA[(Content of {string} without any conversion/escaping)]]>
 *
 * Inline notation
 * ---------------
 *
 * ::
 *
 *     {string -> f:format.cdata()}
 *
 * Output::
 *
 *     <![CDATA[(Content of {string} without any conversion/escaping)]]>
 *
 * @api
 */
class CdataViewHelper extends AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    /**
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        $this->registerArgument('value', 'mixed', 'The value to output');
    }

    /**
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        return sprintf('<![CDATA[%s]]>', $renderChildrenClosure());
    }
}
