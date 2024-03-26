<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Parser\SyntaxTree;

use TYPO3Fluid\Fluid\Core\Compiler\TemplateCompiler;
use TYPO3Fluid\Fluid\Core\Parser\BooleanParser;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * A node which is used inside boolean arguments
 *
 * @internal
 * @todo Make class final.
 */
class BooleanNode extends AbstractNode
{
    /**
     * Stack of expression nodes to be evaluated
     *
     * @var NodeInterface[]
     */
    protected $childNodes = [];

    /**
     * @var array
     */
    protected array $stack = [];

    /**
     * @param mixed $input NodeInterface, array (of nodes or expression parts) or a simple type that can be evaluated to boolean
     */
    public function __construct($input)
    {
        // First, evaluate everything that is not an ObjectAccessorNode, ArrayNode
        // or ViewHelperNode so we get all text, numbers, comparators and
        // groupers from the text parts of the expression. All other nodes
        // we leave intact for later processing
        if ($input instanceof RootNode) {
            $this->stack = $input->getChildNodes();
        } elseif (is_array($input)) {
            $this->stack = $input;
        } else {
            $this->stack = [is_string($input) ? trim($input) : $input];
        }
    }

    public function getStack(): array
    {
        return $this->stack;
    }

    public function evaluate(RenderingContextInterface $renderingContext): bool
    {
        return self::evaluateStack($renderingContext, $this->stack);
    }

    /**
     * @param NodeInterface $node
     * @param RenderingContextInterface $renderingContext
     * @return bool
     */
    public static function createFromNodeAndEvaluate(NodeInterface $node, RenderingContextInterface $renderingContext)
    {
        $booleanNode = new BooleanNode($node);
        return $booleanNode->evaluate($renderingContext);
    }

    /**
     * Takes a stack of nodes evaluates it with the end result
     * being a single boolean value. Creates new BooleanNodes
     * recursively to process braced expressions as single units.
     *
     * @param RenderingContextInterface $renderingContext
     * @param array $expressionParts
     * @return bool the boolean value
     */
    public static function evaluateStack(RenderingContextInterface $renderingContext, array $expressionParts): bool
    {
        $expression = static::reconcatenateExpression($expressionParts);
        $context = static::gatherContext($renderingContext, $expressionParts);

        $parser = new BooleanParser();
        return static::convertToBoolean($parser->evaluate($expression, $context), $renderingContext);
    }

    /**
     * Walk all expressionParts and concatenate an expression string
     */
    public static function reconcatenateExpression(array $expressionParts): string
    {
        $merged = [];
        foreach ($expressionParts as $key => $expressionPart) {
            if ($expressionPart instanceof TextNode || is_string($expressionPart)) {
                $merged[] = $expressionPart instanceof TextNode ? $expressionPart->getText() : $expressionPart;
            } elseif ($expressionPart instanceof NodeInterface) {
                $merged[] = '{node' . $key . '}';
            } else {
                $merged[] = '{node' . $key . '}';
            }
        }
        return implode('', $merged);
    }

    /**
     * Walk all expressionParts and gather a context array of all non textNode parts
     */
    public static function gatherContext(RenderingContextInterface $renderingContext, array $expressionParts): array
    {
        $context = [];
        foreach ($expressionParts as $key => $expressionPart) {
            if ($expressionPart instanceof NodeInterface) {
                $context['node' . $key] = $expressionPart->evaluate($renderingContext);
            } else {
                $context['node' . $key] = $expressionPart;
            }
        }
        return $context;
    }

    /**
     * Convert argument strings to their equivalents. Needed to handle strings with a boolean meaning.
     *
     * Must be public and static as it is used from inside cached templates.
     */
    public static function convertToBoolean(mixed $value, RenderingContextInterface $renderingContext): bool
    {
        if (is_bool($value)) {
            return $value;
        }
        if (is_numeric($value)) {
            return (bool)((float)$value);
        }
        if (is_string($value)) {
            if (strlen($value) === 0) {
                return false;
            }
            $value = $renderingContext->getTemplateParser()->unquoteString($value);
            return strtolower($value) !== 'false' && !empty($value);
        }
        if (is_array($value) || $value instanceof \Countable) {
            return count($value) > 0;
        }
        if (is_object($value)) {
            return true;
        }
        return false;
    }

    public function convert(TemplateCompiler $templateCompiler): array
    {
        $stack = (new ArrayNode($this->getStack()))->convert($templateCompiler);
        $initializationPhpCode = $stack['initialization'] . chr(10);

        $parser = new BooleanParser();
        $compiledExpression = $parser->compile(BooleanNode::reconcatenateExpression($this->getStack()));
        $functionName = $templateCompiler->variableName('expression');
        $initializationPhpCode .= $functionName . ' = function($context) {return ' . $compiledExpression . ';};' . chr(10);

        // @todo: There are possible paths to short-circuit this code in combination
        //        with EscapingNode, to hint EscapingNode no further sanitation
        //        is needed. See PR #440 for options on this. The PR however introduces
        //        multiple different values for 'execution', which needs to be decided
        //        if we really want this.
        return [
            'initialization' => $initializationPhpCode,
            'execution' => sprintf(
                '%s::convertToBoolean(' . chr(10) .
                '    %s(%s::gatherContext($renderingContext, %s)),' . chr(10) .
                '    $renderingContext' . chr(10) .
                ')',
                BooleanNode::class,
                $functionName,
                BooleanNode::class,
                $stack['execution']
            )
        ];
    }
}
