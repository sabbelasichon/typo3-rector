<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\ViewHelper;

use TYPO3Fluid\Fluid\Core\Compiler\TemplateCompiler;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\NodeInterface;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\ViewHelperNode;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * This view helper is an abstract ViewHelper which implements an if/else condition.
 *
 * = Usage =
 *
 * To create a custom Condition ViewHelper, you need to subclass this class, and
 * implement your own render() method. Inside there, you should call $this->renderThenChild()
 * if the condition evaluated to TRUE, and $this->renderElseChild() if the condition evaluated
 * to FALSE.
 *
 * Every Condition ViewHelper has a "then" and "else" argument, so it can be used like:
 * <[aConditionViewHelperName] .... then="condition true" else="condition false" />,
 * or as well use the "then" and "else" child nodes.
 *
 * @see TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper for a more detailed explanation and a simple usage example.
 * Make sure to NOT OVERRIDE the constructor.
 *
 * @api
 */
abstract class AbstractConditionViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Initializes the "then" and "else" arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('then', 'mixed', 'Value to be returned if the condition if met.', false, null, true);
        $this->registerArgument('else', 'mixed', 'Value to be returned if the condition if not met.', false, null, true);
    }

    /**
     * Renders <f:then> child if $condition is true, otherwise renders <f:else> child.
     * Method which only gets called if the template is not compiled. For static calling,
     * the then/else nodes are converted to closures and condition evaluation closures.
     *
     * @return string the rendered string
     * @api
     */
    public function render()
    {
        if (static::verdict($this->arguments, $this->renderingContext)) {
            return $this->renderThenChild();
        }
        return $this->renderElseChild();
    }

    /**
     * @param array<string, mixed> $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $mainConditionVerdict = static::verdict($arguments, $renderingContext);
        if ($mainConditionVerdict) {
            // The condition argument evaluated to true. Return the "then" argument as string,
            // execute f:then child or general body closure, or return empty string.
            if (isset($arguments['then'])) {
                return $arguments['then'];
            }
            if (isset($arguments['__then'])) {
                return $arguments['__then']();
            }
            return '';
        }
        if (!empty($arguments['__elseIf'])) {
            // The condition argument evaluated to false. For each "f:else if",
            // evaluate its condition and return its executed body closure if verdict is true.
            foreach ($arguments['__elseIf'] as $elseIf) {
                if ($elseIf['condition']()) {
                    return $elseIf['body']();
                }
            }
        }
        if (isset($arguments['else'])) {
            // The condition argument evaluated to false. If there
            // is an else argument, return as string.
            return $arguments['else'];
        }
        if (!empty($arguments['__else'])) {
            // The condition argument evaluated to false. If there is
            // an f:else body closure, return its executed body.
            return $arguments['__else']();
        }
        return '';
    }

    /**
     * Static method which can be overridden by subclasses. If a subclass
     * requires a different (or faster) decision then this method is the one
     * to override and implement.
     *
     * @param array<string, mixed> $arguments
     * @param RenderingContextInterface $renderingContext
     * @return bool
     */
    public static function verdict(array $arguments, RenderingContextInterface $renderingContext)
    {
        return static::evaluateCondition($arguments);
    }

    /**
     * Static method which can be overridden by subclasses. If a subclass
     * requires a different (or faster) decision then this method is the one
     * to override and implement.
     *
     * Note: method signature does not type-hint that an array is desired,
     * and as such, *appears* to accept any input type. There is no type hint
     * here for legacy reasons - the signature is kept compatible with third
     * party packages which depending on PHP version would error out if this
     * signature was not compatible with that of existing and in-production
     * subclasses that will be using this base class in the future. Let this
     * be a warning if someone considers changing this method signature!
     *
     * @deprecated Deprecated in favor of ClassName::verdict($arguments, renderingContext), will no longer be called in 3.0
     * @param array<string, mixed> $arguments
     * @return bool
     * @api
     */
    protected static function evaluateCondition($arguments = null)
    {
        return isset($arguments['condition']) && (bool)($arguments['condition']);
    }

    /**
     * Returns value of "then" attribute.
     * If then attribute is not set, iterates through child nodes and renders ThenViewHelper.
     * If then attribute is not set and no ThenViewHelper and no ElseViewHelper is found, all child nodes are rendered
     *
     * @return mixed rendered ThenViewHelper or contents of <f:if> if no ThenViewHelper was found
     * @api
     */
    protected function renderThenChild()
    {
        if ($this->hasArgument('then')) {
            return $this->arguments['then'];
        }

        $elseViewHelperEncountered = false;
        foreach ($this->viewHelperNode->getChildNodes() as $childNode) {
            if ($childNode instanceof ViewHelperNode
                && substr($childNode->getViewHelperClassName(), -14) === 'ThenViewHelper') {
                $data = $childNode->evaluate($this->renderingContext);
                return $data;
            }
            if ($childNode instanceof ViewHelperNode
                && substr($childNode->getViewHelperClassName(), -14) === 'ElseViewHelper') {
                $elseViewHelperEncountered = true;
            }
        }

        if ($elseViewHelperEncountered) {
            return '';
        }
        return $this->renderChildren();
    }

    /**
     * Returns value of "else" attribute.
     * If else attribute is not set, iterates through child nodes and renders ElseViewHelper.
     * If else attribute is not set and no ElseViewHelper is found, an empty string will be returned.
     *
     * @return string rendered ElseViewHelper or an empty string if no ThenViewHelper was found
     * @api
     */
    protected function renderElseChild()
    {
        if ($this->hasArgument('else')) {
            return $this->arguments['else'];
        }

        /** @var ViewHelperNode|null $elseNode */
        $elseNode = null;
        foreach ($this->viewHelperNode->getChildNodes() as $childNode) {
            if ($childNode instanceof ViewHelperNode
                && substr($childNode->getViewHelperClassName(), -14) === 'ElseViewHelper') {
                $arguments = $childNode->getArguments();
                if (isset($arguments['if'])) {
                    if ($arguments['if']->evaluate($this->renderingContext)) {
                        return $childNode->evaluate($this->renderingContext);
                    }
                } else {
                    $elseNode = $childNode;
                }
            }
        }

        return $elseNode instanceof ViewHelperNode ? $elseNode->evaluate($this->renderingContext) : '';
    }

    /**
     * Optimized version combining default convert() / compile() into one
     * method: The condition VHs dissect children and looks for then, else
     * and elseif nodes, separates them and puts them into special "__"
     * prefixed arguments. The default renderChildrenClosure is not needed
     * and skipped.
     */
    final public function convert(TemplateCompiler $templateCompiler): array
    {
        $node = $this->viewHelperNode;

        $argumentsVariableName = $templateCompiler->variableName('arguments');
        $argumentInitializationCode = sprintf('%s = [' . chr(10), $argumentsVariableName);

        $accumulatedArgumentInitializationCode = '';
        $arguments = $node->getArguments();
        $argumentDefinitions = $node->getArgumentDefinitions();
        foreach ($argumentDefinitions as $argumentName => $argumentDefinition) {
            if (!array_key_exists($argumentName, $arguments)) {
                // Argument *not* given to VH, use default value
                $defaultValue = $argumentDefinition->getDefaultValue();
                $argumentInitializationCode .= sprintf(
                    '\'%s\' => %s,' . chr(10),
                    $argumentName,
                    is_array($defaultValue) && empty($defaultValue) ? '[]' : var_export($defaultValue, true)
                );
            } elseif ($arguments[$argumentName] instanceof NodeInterface) {
                // Argument *is* given to VH and is a node, resolve
                $converted = $arguments[$argumentName]->convert($templateCompiler);
                $accumulatedArgumentInitializationCode .= $converted['initialization'];
                $argumentInitializationCode .= sprintf(
                    '\'%s\' => %s,' . chr(10),
                    $argumentName,
                    $converted['execution']
                );
            } else {
                // Argument *is* given to VH and is a simple type.
                // @todo: Why is this not a node object as well? See f:if inline syntax tests.
                $argumentInitializationCode .= sprintf(
                    '\'%s\' => %s,' . chr(10),
                    $argumentName,
                    $arguments[$argumentName]
                );
            }
        }

        $thenChildEncountered = false;
        $elseChildEncountered = false;
        $elseIfCounter = 0;
        $elseIfCode = '\'__elseIf\' => [' . chr(10);
        foreach ($node->getChildNodes() as $childNode) {
            if ($childNode instanceof ViewHelperNode) {
                $viewHelperClassName = $childNode->getViewHelperClassName();
                if (!$thenChildEncountered && str_ends_with($viewHelperClassName, 'ThenViewHelper')) {
                    // If there are multiple f:then children, we pick the first one only.
                    // This is in line with the non-compiled behavior.
                    $thenChildEncountered = true;
                    $argumentInitializationCode .= sprintf(
                        '\'__then\' => %s,' . chr(10),
                        $templateCompiler->wrapChildNodesInClosure($childNode)
                    );
                    continue;
                }
                if (str_ends_with($viewHelperClassName, 'ElseViewHelper')) {
                    if (isset($childNode->getArguments()['if'])) {
                        // This "f:else" has the "if" argument, indicating this is a secondary (elseif) condition.
                        // Compile a closure which will evaluate the condition.
                        $elseIfCode .= sprintf(
                            '    %s => [' . chr(10) .
                            '        \'condition\' => %s,' . chr(10) .
                            '        \'body\' => %s' . chr(10) .
                            '    ],' . chr(10),
                            $elseIfCounter,
                            $templateCompiler->wrapViewHelperNodeArgumentEvaluationInClosure($childNode, 'if'),
                            $templateCompiler->wrapChildNodesInClosure($childNode)
                        );
                        $elseIfCounter++;
                        continue;
                    }
                    if (!$elseChildEncountered) {
                        // If there are multiple f:else children, we pick the first one only.
                        // This is in line with the non-compiled behavior.
                        $elseChildEncountered = true;
                        $argumentInitializationCode .= sprintf(
                            '\'__else\' => %s,' . chr(10),
                            $templateCompiler->wrapChildNodesInClosure($childNode)
                        );
                    }
                }
            }
        }
        if (!$thenChildEncountered && $elseIfCounter === 0 && !$elseChildEncountered && !isset($node->getArguments()['then'])) {
            // If there is no then argument, and there are neither "f:then", "f:else" nor "f:else if" children,
            // then the entire body is considered the "then" child.
            $argumentInitializationCode .= sprintf(
                '\'__then\' => %s,' . chr(10),
                $templateCompiler->wrapChildNodesInClosure($node)
            );
        }

        if ($elseIfCounter > 0) {
            $elseIfCode .= '],' . chr(10);
            $argumentInitializationCode .= $elseIfCode;
        }
        $argumentInitializationCode .= '];' . chr(10);

        return [
            'initialization' => '// Rendering ViewHelper ' . $node->getViewHelperClassName() . chr(10) .
                $accumulatedArgumentInitializationCode . chr(10) .
                $argumentInitializationCode,
            'execution' => sprintf(
                '%s::renderStatic(%s, static fn() => \'\', $renderingContext)' . chr(10),
                get_class($this),
                $argumentsVariableName,
            )
        ];
    }
}
