<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

/**
 * Variable assigning ViewHelper
 *
 * Assigns one template variable which will exist also
 * after the ViewHelper is done rendering, i.e. adds
 * template variables.
 *
 * If you require a variable assignment which does not
 * exist in the template after a piece of Fluid code
 * is rendered, consider using ``f:alias`` ViewHelper instead.
 *
 * Usages:
 *
 * ::
 *
 *     {f:variable(name: 'myvariable', value: 'some value')}
 *     <f:variable name="myvariable">some value</f:variable>
 *     {oldvariable -> f:format.htmlspecialchars() -> f:variable(name: 'newvariable')}
 *     <f:variable name="myvariable"><f:format.htmlspecialchars>{oldvariable}</f:format.htmlspecialchars></f:variable>
 *
 * @see \TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper
 * @api
 */
class VariableViewHelper extends AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    public function initializeArguments()
    {
        $this->registerArgument('value', 'mixed', 'Value to assign. If not in arguments then taken from tag content');
        $this->registerArgument('name', 'string', 'Name of variable to create', true);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $value = $renderChildrenClosure();
        $renderingContext->getVariableProvider()->add($arguments['name'], $value);
    }
}
