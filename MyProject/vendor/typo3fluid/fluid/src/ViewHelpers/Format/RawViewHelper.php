<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\Compiler\TemplateCompiler;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\ViewHelperNode;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

/**
 * Outputs an argument/value without any escaping. Is normally used to output
 * an ObjectAccessor which should not be escaped, but output as-is.
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
 *     <f:format.raw>{string}</f:format.raw>
 *
 * Output::
 *
 *     (Content of ``{string}`` without any conversion/escaping)
 *
 * Value attribute
 * ---------------
 *
 * ::
 *
 *     <f:format.raw value="{string}" />
 *
 * Output::
 *
 *     (Content of ``{string}`` without any conversion/escaping)
 *
 * Inline notation
 * ---------------
 *
 * ::
 *
 *     {string -> f:format.raw()}
 *
 * Output::
 *
 *     (Content of ``{string}`` without any conversion/escaping)
 *
 * @api
 */
class RawViewHelper extends AbstractViewHelper
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
        $this->registerArgument('value', 'mixed', 'The value to output', false, null, false);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        return $renderChildrenClosure();
    }

    /**
     * @param string $argumentsName
     * @param string $closureName
     * @param string $initializationPhpCode
     * @param ViewHelperNode $node
     * @param TemplateCompiler $compiler
     * @return mixed
     */
    public function compile($argumentsName, $closureName, &$initializationPhpCode, ViewHelperNode $node, TemplateCompiler $compiler)
    {
        $contentArgumentName = $this->resolveContentArgumentName();
        return sprintf(
            'isset(%s[\'%s\']) ? %s[\'%s\'] : %s()',
            $argumentsName,
            $contentArgumentName,
            $argumentsName,
            $contentArgumentName,
            $closureName
        );
    }
}
