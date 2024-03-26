<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Compiler\TemplateCompiler;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\NodeInterface;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\ViewHelperNode;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Switch ViewHelper which can be used to render content depending on a value or expression.
 * Implements what a basic PHP ``switch()`` does.
 *
 * An optional default case can be specified which is rendered if none of the
 * ``case`` conditions matches.
 *
 * Using this ViewHelper can be a sign of weak architecture. If you end up using it extensively
 * you might want to consider restructuring your controllers/actions and/or use partials and sections.
 * E.g. the above example could be achieved with :html:`<f:render partial="title.{person.gender}" />`
 * and the partials "title.male.html", "title.female.html", ...
 * Depending on the scenario this can be easier to extend and possibly contains less duplication.
 *
 * Examples
 * ========
 *
 * Simple Switch statement
 * -----------------------
 *
 * ::
 *
 *     <f:switch expression="{person.gender}">
 *         <f:case value="male">Mr.</f:case>
 *         <f:case value="female">Mrs.</f:case>
 *         <f:defaultCase>Mr. / Mrs.</f:defaultCase>
 *     </f:switch>
 *
 * Output::
 *
 *     "Mr.", "Mrs." or "Mr. / Mrs." (depending on the value of {person.gender})
 *
 * @api
 */
class SwitchViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * @var mixed
     */
    protected $backupSwitchExpression;

    /**
     * @var bool
     */
    protected $backupBreakState = false;

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('expression', 'mixed', 'Expression to switch', true);
    }

    /**
     * @return string the rendered string
     * @api
     */
    public function render()
    {
        $expression = $this->arguments['expression'];
        $this->backupSwitchState();
        $variableContainer = $this->renderingContext->getViewHelperVariableContainer();

        $variableContainer->addOrUpdate(SwitchViewHelper::class, 'switchExpression', $expression);
        $variableContainer->addOrUpdate(SwitchViewHelper::class, 'break', false);

        $content = $this->retrieveContentFromChildNodes($this->viewHelperNode->getChildNodes());

        if ($variableContainer->exists(SwitchViewHelper::class, 'switchExpression')) {
            $variableContainer->remove(SwitchViewHelper::class, 'switchExpression');
        }
        if ($variableContainer->exists(SwitchViewHelper::class, 'break')) {
            $variableContainer->remove(SwitchViewHelper::class, 'break');
        }

        $this->restoreSwitchState();
        return $content ?? '';
    }

    /**
     * @param NodeInterface[] $childNodes
     * @return mixed
     */
    protected function retrieveContentFromChildNodes(array $childNodes)
    {
        $content = null;
        $defaultCaseViewHelperNode = null;
        foreach ($childNodes as $childNode) {
            if ($this->isDefaultCaseNode($childNode)) {
                $defaultCaseViewHelperNode = $childNode;
            }
            if (!$this->isCaseNode($childNode)) {
                continue;
            }
            $content = $childNode->evaluate($this->renderingContext);
            if ($this->viewHelperVariableContainer->get(SwitchViewHelper::class, 'break') === true) {
                $defaultCaseViewHelperNode = null;
                break;
            }
        }

        if ($defaultCaseViewHelperNode !== null) {
            $content = $defaultCaseViewHelperNode->evaluate($this->renderingContext);
        }
        return $content;
    }

    /**
     * @param NodeInterface $node
     * @return bool
     */
    protected function isDefaultCaseNode(NodeInterface $node)
    {
        return $node instanceof ViewHelperNode && $node->getViewHelperClassName() === DefaultCaseViewHelper::class;
    }

    /**
     * @param NodeInterface $node
     * @return bool
     */
    protected function isCaseNode(NodeInterface $node)
    {
        return $node instanceof ViewHelperNode && $node->getViewHelperClassName() === CaseViewHelper::class;
    }

    /**
     * Backups "switch expression" and "break" state of a possible parent switch ViewHelper to support nesting
     */
    protected function backupSwitchState()
    {
        if ($this->renderingContext->getViewHelperVariableContainer()->exists(SwitchViewHelper::class, 'switchExpression')) {
            $this->backupSwitchExpression = $this->renderingContext->getViewHelperVariableContainer()->get(SwitchViewHelper::class, 'switchExpression');
        }
        if ($this->renderingContext->getViewHelperVariableContainer()->exists(SwitchViewHelper::class, 'break')) {
            $this->backupBreakState = $this->renderingContext->getViewHelperVariableContainer()->get(SwitchViewHelper::class, 'break');
        }
    }

    /**
     * Restores "switch expression" and "break" states that might have been backed up in backupSwitchState() before
     */
    protected function restoreSwitchState()
    {
        if ($this->backupSwitchExpression !== null) {
            $this->renderingContext->getViewHelperVariableContainer()->addOrUpdate(SwitchViewHelper::class, 'switchExpression', $this->backupSwitchExpression);
        }
        if ($this->backupBreakState !== false) {
            $this->renderingContext->getViewHelperVariableContainer()->addOrUpdate(SwitchViewHelper::class, 'break', true);
        }
    }

    /**
     * Compiles the node structure to a native switch
     * statement which evaluates closures for each
     * case comparison and renders child node closures
     * only when value matches.
     *
     * @param string $argumentsName
     * @param string $closureName
     * @param string $initializationPhpCode
     * @param ViewHelperNode $node
     * @param TemplateCompiler $compiler
     * @return string
     */
    public function compile($argumentsName, $closureName, &$initializationPhpCode, ViewHelperNode $node, TemplateCompiler $compiler)
    {
        $phpCode = 'call_user_func_array(function($arguments) use ($renderingContext) {' . PHP_EOL .
            'switch ($arguments[\'expression\']) {' . PHP_EOL;
        $hasDefaultCase = false;
        foreach ($node->getChildNodes() as $childNode) {
            if ($this->isDefaultCaseNode($childNode)) {
                $hasDefaultCase = true;
                $childrenClosure = $compiler->wrapChildNodesInClosure($childNode);
                $phpCode .= sprintf('default: return call_user_func(%s);', $childrenClosure) . PHP_EOL;
            } elseif ($this->isCaseNode($childNode)) {
                /** @var ViewHelperNode $childNode */
                $valueClosure = $compiler->wrapViewHelperNodeArgumentEvaluationInClosure($childNode, 'value');
                $childrenClosure = $compiler->wrapChildNodesInClosure($childNode);
                $phpCode .= sprintf(
                    'case call_user_func(%s): return call_user_func(%s);',
                    $valueClosure,
                    $childrenClosure
                ) . PHP_EOL;
            }
        }
        if (!$hasDefaultCase) {
            $phpCode .= 'default:' . PHP_EOL;
            $phpCode .= 'return \'\';' . PHP_EOL;
        }
        $phpCode .= '}' . PHP_EOL;
        $phpCode .= sprintf('}, array(%s))', $argumentsName);
        return $phpCode;
    }
}
