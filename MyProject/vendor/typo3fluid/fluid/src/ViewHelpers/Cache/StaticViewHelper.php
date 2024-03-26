<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\ViewHelpers\Cache;

use TYPO3Fluid\Fluid\Core\Compiler\TemplateCompiler;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to force compiling to a static string
 *
 * Used around chunks of template code where you want the
 * output of said template code to be compiled to a static
 * string (rather than a collection of compiled nodes, as
 * is the usual behavior).
 *
 * The effect is that none of the child ViewHelpers or nodes
 * used inside this tag will be evaluated when rendering the
 * template once it is compiled. It will essentially replace
 * all logic inside the tag with a plain string output.
 *
 * Works by turning the ``compile`` method into a method that
 * renders the child nodes and returns the resulting content
 * directly as a string variable.
 *
 * You can use this with great effect to further optimise the
 * performance of your templates: in use cases where chunks of
 * template code depend on static variables (like thoese in
 * ``{settings}`` for example) and those variables never change,
 * and the template uses no other dynamic variables, forcing
 * the template to compile that chunk to a static string can
 * save a lot of operations when rendering the compiled template.
 *
 * NB: NOT TO BE USED FOR CACHING ANYTHING OTHER THAN STRING-
 * COMPATIBLE OUTPUT!
 *
 * USE WITH CARE! WILL PRESERVE EVERYTHING RENDERED, INCLUDING
 * POTENTIALLY SENSITIVE DATA CONTAINED IN OUTPUT!
 *
 * Examples
 * ========
 *
 * Usage and effect
 * ----------------
 *
 * ::
 *
 *     <f:if condition="{var}">Is always evaluated also when compiled</f:if>
 *     <f:cache.static>
 *         <f:if condition="{othervar}">
 *             Will only be evaluated once and this output will be
 *             cached as a static string with no logic attached.
 *             The compiled template will not contain neither the
 *             condition ViewHelperNodes or the variable accessor
 *             that are used inside this node.
 *         </f:if>
 *     </f:cache.static>
 *
 * This is also evaluated when compiled (static node is closed)::
 *
 *     <f:if condition="{var}">Also evaluated; is outside static node</f:if>
 *
 * @api
 */
class StaticViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * @return string
     */
    public function render()
    {
        return $this->renderChildren();
    }

    final public function convert(TemplateCompiler $templateCompiler): array
    {
        return [
            'initialization' => '// Rendering ViewHelper ' . $this->viewHelperNode->getViewHelperClassName() . chr(10),
            'execution' => '\'' . str_replace("'", "\'", $this->viewHelperNode->evaluateChildNodes($this->renderingContext)) . '\'',
        ];
    }
}
