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
 * Inline Fluid rendering ViewHelper
 *
 * Renders Fluid code stored in a variable, which you normally would
 * have to render before assigning it to the view. Instead you can
 * do the following (note, extremely simplified use case)::
 *
 *      $view->assign('variable', 'value of my variable');
 *      $view->assign('code', 'My variable: {variable}');
 *
 * And in the template::
 *
 *      {code -> f:inline()}
 *
 * Which outputs::
 *
 *      My variable: value of my variable
 *
 * You can use this to pass smaller and dynamic pieces of Fluid code
 * to templates, as an alternative to creating new partial templates.
 */
class InlineViewHelper extends AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    protected $escapeChildren = false;

    protected $escapeOutput = false;

    public function initializeArguments()
    {
        $this->registerArgument(
            'code',
            'string',
            'Fluid code to be rendered as if it were part of the template rendering it. Can be passed as inline argument or tag content'
        );
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed|string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        return $renderingContext->getTemplateParser()->parse((string)$renderChildrenClosure())->render($renderingContext);
    }
}
