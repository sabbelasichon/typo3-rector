<?php

declare(strict_types=1);

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Math Expression Syntax Node - is a container for numeric values.
 *
 * @internal
 * @todo Make class final.
 */
class MathExpressionNode extends AbstractExpressionNode
{
    /**
     * Pattern which detects the mathematical expressions with either
     * object accessor expressions or numbers on left and right hand
     * side of a mathematical operator inside curly braces, e.g.:
     *
     * {variable * 10}, {100 / variable}, {variable + variable2} etc.
     */
    public static $detectionExpression = '/
		(
			{                                # Start of shorthand syntax
				(?:                          # Math expression is composed of...
					[_a-zA-Z0-9\.]+(?:[\s]*[*+\^\/\%\-]{1}[\s]*[_a-zA-Z0-9\.]+)+   # Various math expressions left and right sides with any spaces
					|(?R)                    # Other expressions inside
				)+
			}                                # End of shorthand syntax
		)/x';

    /**
     * @param RenderingContextInterface $renderingContext
     * @param string $expression
     * @param array $matches
     * @return int|float
     */
    public static function evaluateExpression(RenderingContextInterface $renderingContext, $expression, array $matches): int|float
    {
        // Split the expression on all recognized operators
        $matches = [];
        preg_match_all('/([+\-*\^\/\%]|[_a-zA-Z0-9\.]+)/s', $expression, $matches);
        $matches[0] = array_map('trim', $matches[0]);
        // Like the BooleanNode, we dumb down the processing logic to not apply
        // any special precedence on the priority of operators. We simply process
        // them in order.
        $result = array_shift($matches[0]);
        $result = static::getTemplateVariableOrValueItself($result, $renderingContext);
        $result = ($result == (int)$result) ? (int)$result : (float)$result;
        $operator = null;
        $operators = ['*', '^', '-', '+', '/', '%'];
        foreach ($matches[0] as $part) {
            if (in_array($part, $operators)) {
                $operator = $part;
            } else {
                $part = static::getTemplateVariableOrValueItself($part, $renderingContext);
                $part = ($part == (int)$part) ? (int)$part : (float)$part;
                $result = self::evaluateOperation($result, $operator, $part);
            }
        }
        return $result;
    }

    protected static function evaluateOperation(int|float $left, ?string $operator, int|float $right): int|float
    {
        if ($operator === '%') {
            return $left % $right;
        }
        if ($operator === '-') {
            return $left - $right;
        }
        if ($operator === '+') {
            return $left + $right;
        }
        if ($operator === '*') {
            return $left * $right;
        }
        if ($operator === '/') {
            return (int)$right !== 0 ? $left / $right : 0;
        }
        if ($operator === '^') {
            return pow($left, $right);
        }
        return 0;
    }
}
