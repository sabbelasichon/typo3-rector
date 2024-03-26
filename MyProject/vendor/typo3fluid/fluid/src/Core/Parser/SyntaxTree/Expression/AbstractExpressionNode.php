<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression;

use TYPO3Fluid\Fluid\Core\Compiler\TemplateCompiler;
use TYPO3Fluid\Fluid\Core\Parser;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\AbstractNode;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\Variables\StandardVariableProvider;

/**
 * Base class for nodes based on (shorthand) expressions.
 */
abstract class AbstractExpressionNode extends AbstractNode implements ExpressionNodeInterface
{
    /**
     * Contents of the text node
     *
     * @var string
     */
    protected $expression;

    /**
     * @var array
     */
    protected $matches = [];

    /**
     * Constructor.
     *
     * @param string $expression The original expression that created this node.
     * @param array $matches Matches extracted from expression
     * @throws Parser\Exception
     */
    public function __construct($expression, array $matches)
    {
        $this->expression = trim($expression, " \t\n\r\0\x0b");
        $this->matches = $matches;
    }

    /**
     * Evaluates the expression stored in this node, in the context of $renderingcontext.
     *
     * @param RenderingContextInterface $renderingContext
     * @return string the text stored in this node/subtree.
     */
    public function evaluate(RenderingContextInterface $renderingContext)
    {
        try {
            return static::evaluateExpression($renderingContext, $this->expression, $this->matches);
        } catch (ExpressionException $exception) {
            return $renderingContext->getErrorHandler()->handleExpressionError($exception);
        }
    }

    /**
     * Compiles the ExpressionNode, returning an array with
     * exactly two keys which contain strings:
     *
     * - "initialization" which contains variable initializations
     * - "execution" which contains the execution (that uses the variables)
     *
     * The expression and matches can be read from the local
     * instance - and the RenderingContext and other APIs
     * can be accessed via the TemplateCompiler.
     *
     * @param TemplateCompiler $templateCompiler
     * @return array
     */
    public function compile(TemplateCompiler $templateCompiler)
    {
        $handlerClass = get_class($this);
        $expressionVariable = $templateCompiler->variableName('string');
        $matchesVariable = $templateCompiler->variableName('array');
        $initializationPhpCode = sprintf('// Rendering %s node' . chr(10), $handlerClass);
        $initializationPhpCode .= sprintf('%s = \'%s\';', $expressionVariable, $this->getExpression()) . chr(10);
        $initializationPhpCode .= sprintf('%s = %s;', $matchesVariable, var_export($this->getMatches(), true)) . chr(10);
        return [
            'initialization' => $initializationPhpCode,
            'execution' => sprintf(
                '\%s::evaluateExpression($renderingContext, %s, %s)',
                $handlerClass,
                $expressionVariable,
                $matchesVariable
            )
        ];
    }

    /**
     * Getter for returning the expression before parsing.
     *
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @return array
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * @param string $part
     * @return string
     */
    protected static function trimPart($part)
    {
        return trim($part, " \t\n\r\0\x0b{}");
    }

    /**
     * @param mixed $candidate
     * @param RenderingContextInterface $renderingContext
     * @return mixed
     */
    protected static function getTemplateVariableOrValueItself($candidate, RenderingContextInterface $renderingContext)
    {
        $variables = $renderingContext->getVariableProvider()->getAll();
        $standardVariableProvider = new StandardVariableProvider();
        $standardVariableProvider->setSource($variables);
        $suspect = $standardVariableProvider->getByPath($candidate);
        return $suspect ?? $candidate;
    }

    public function convert(TemplateCompiler $templateCompiler): array
    {
        return $this->compile($templateCompiler);
    }
}
