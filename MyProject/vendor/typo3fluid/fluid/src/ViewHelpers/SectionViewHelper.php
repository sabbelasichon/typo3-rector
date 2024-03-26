<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Compiler\TemplateCompiler;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\TextNode;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\ViewHelperNode;
use TYPO3Fluid\Fluid\Core\Variables\VariableProviderInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * A ViewHelper to declare sections in templates for later use with e.g. the ``f:render`` ViewHelper.
 *
 * Examples
 * ========
 *
 * Rendering sections
 * ------------------
 *
 * ::
 *
 *     <f:section name="someSection">This is a section. {foo}</f:section>
 *     <f:render section="someSection" arguments="{foo: someVariable}" />
 *
 * Output::
 *
 *     the content of the section "someSection". The content of the variable {someVariable} will be available in the partial as {foo}
 *
 * Rendering recursive sections
 * ----------------------------
 *
 * ::
 *
 *     <f:section name="mySection">
 *        <ul>
 *             <f:for each="{myMenu}" as="menuItem">
 *                  <li>
 *                    {menuItem.text}
 *                    <f:if condition="{menuItem.subItems}">
 *                        <f:render section="mySection" arguments="{myMenu: menuItem.subItems}" />
 *                    </f:if>
 *                  </li>
 *             </f:for>
 *        </ul>
 *     </f:section>
 *     <f:render section="mySection" arguments="{myMenu: menu}" />
 *
 * Output::
 *
 *     <ul>
 *         <li>menu1
 *             <ul>
 *                 <li>menu1a</li>
 *                 <li>menu1b</li>
 *             </ul>
 *         </li>
 *     [...]
 *     (depending on the value of {menu})
 *
 * @api
 */
class SectionViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Initialize the arguments.
     *
     * @api
     */
    public function initializeArguments()
    {
        $this->registerArgument('name', 'string', 'Name of the section', true);
    }

    /**
     * Rendering directly returns all child nodes.
     *
     * @return string HTML String of all child nodes.
     * @api
     */
    public function render()
    {
        $content = '';
        if ($this->viewHelperVariableContainer->exists(SectionViewHelper::class, 'isCurrentlyRenderingSection')) {
            $this->viewHelperVariableContainer->remove(SectionViewHelper::class, 'isCurrentlyRenderingSection');
            $content = $this->renderChildren();
        }
        return $content;
    }

    /**
     * This VH does not ever output anything as such: Sections are
     * handled differently in the compiler / parser and the f:render
     * VH invokes section body execution.
     * We optimize compilation to always return an empty here.
     */
    final public function convert(TemplateCompiler $templateCompiler): array
    {
        return [
            'initialization' => '',
            'execution' => '\'\'',
        ];
    }

    /**
     * Save the associated ViewHelper node in a static public class variable.
     * called directly after the ViewHelper was built.
     *
     * @param ViewHelperNode $node
     * @param TextNode[] $arguments
     * @param VariableProviderInterface $variableContainer
     */
    public static function postParseEvent(ViewHelperNode $node, array $arguments, VariableProviderInterface $variableContainer)
    {
        $nameArgument = $arguments['name'];
        $sectionName = $nameArgument->getText();
        $sections = $variableContainer['1457379500_sections'] ? $variableContainer['1457379500_sections'] : [];
        $sections[$sectionName] = $node;
        $variableContainer['1457379500_sections'] = $sections;
    }
}
