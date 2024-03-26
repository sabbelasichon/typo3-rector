<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Compiler\TemplateCompiler;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\ViewHelperNode;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Else-Branch of a condition. Only has an effect inside of ``f:if``.
 * See the ``f:if`` ViewHelper for documentation.
 *
 * Examples
 * ========
 *
 * Output content if condition is not met
 * --------------------------------------
 *
 * ::
 *
 *     <f:if condition="{someCondition}">
 *         <f:else>
 *             condition was not true
 *         </f:else>
 *     </f:if>
 *
 * Output::
 *
 *     Everything inside the "else" tag is displayed if the condition evaluates to FALSE.
 *     Otherwise nothing is outputted in this example.
 *
 * @see TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper
 * @api
 */
class ElseViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        $this->registerArgument('if', 'boolean', 'Condition expression conforming to Fluid boolean rules');
    }

    /**
     * @return string the rendered string
     * @api
     */
    public function render()
    {
        return $this->renderChildren();
    }

    /**
     * @param string $argumentsName
     * @param string $closureName
     * @param string $initializationPhpCode
     * @param ViewHelperNode $node
     * @param TemplateCompiler $compiler
     * @return string|null
     */
    public function compile($argumentsName, $closureName, &$initializationPhpCode, ViewHelperNode $node, TemplateCompiler $compiler)
    {
        return '\'\'';
    }
}
