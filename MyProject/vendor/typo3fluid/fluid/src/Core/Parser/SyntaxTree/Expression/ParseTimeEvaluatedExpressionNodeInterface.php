<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\Expression;

/**
 * Signaling interface used in Expression Node types which
 * must be evaluated when the template is parsed. Normal
 * expression nodes only get evaluated when the template
 * is actually rendered - implementing this interface tells
 * Fluid your expression node type must be evaluated when
 * parsed (and again when rendered).
 *
 * Exists only as a temporary measure until parse-time
 * evaluation of expression nodes is removed. For use cases
 * which call for evaluation during parse-time, please see
 * the PreProcessor pattern instead.
 *
 * @deprecated To be removed again in Fluid 3.0
 */
interface ParseTimeEvaluatedExpressionNodeInterface extends ExpressionNodeInterface
{
}
