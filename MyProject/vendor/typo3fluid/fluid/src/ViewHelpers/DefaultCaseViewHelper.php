<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Compiler\TemplateCompiler;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\ViewHelperNode;
use TYPO3Fluid\Fluid\Core\ViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * A ViewHelper which specifies the "default" case when used within the ``f:switch`` ViewHelper.
 *
 * @see \TYPO3Fluid\Fluid\ViewHelpers\SwitchViewHelper
 *
 * @api
 */
class DefaultCaseViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * @return string the contents of this ViewHelper if no other "Case" ViewHelper of the surrounding switch ViewHelper matches
     * @throws ViewHelper\Exception
     * @api
     */
    public function render()
    {
        $viewHelperVariableContainer = $this->renderingContext->getViewHelperVariableContainer();
        if (!$viewHelperVariableContainer->exists(SwitchViewHelper::class, 'switchExpression')) {
            throw new ViewHelper\Exception('The "default case" ViewHelper can only be used within a switch ViewHelper', 1368112037);
        }
        return $this->renderChildren();
    }

    /**
     * @param string $argumentsName
     * @param string $closureName
     * @param string $initializationPhpCode
     * @param ViewHelperNode $node
     * @param TemplateCompiler $compiler
     * @return string
     */
    public function compile($argumentsName, $closureName, &$initializationPhpCode, ViewHelperNode $node, TemplateCompiler $compiler)
    {
        return '\'\'';
    }
}
