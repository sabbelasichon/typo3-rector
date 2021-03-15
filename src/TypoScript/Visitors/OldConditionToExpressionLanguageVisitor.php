<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypoScript\Visitors;

use Helmich\TypoScriptParser\Parser\AST\ConditionalStatement;
use Helmich\TypoScriptParser\Parser\AST\Statement;
use LogicException;
use Ssch\TYPO3Rector\TypoScript\Conditions\TyposcriptConditionMatcher;

/**
 * @see \Ssch\TYPO3Rector\Tests\TypoScript\Visitors\OldConditionToExpressionLanguageVisitorTest
 */
final class OldConditionToExpressionLanguageVisitor extends AbstractVisitor
{
    /**
     * @var TyposcriptConditionMatcher[]
     */
    private $conditionMatchers = [];

    /**
     * @param TyposcriptConditionMatcher[] $conditionMatchers
     */
    public function __construct(array $conditionMatchers = [])
    {
        $this->conditionMatchers = $conditionMatchers;
    }

    public function enterNode(Statement $statement): void
    {
        if ($statement instanceof ConditionalStatement) {
            preg_match_all('#\[(.*)]#imU', $statement->condition, $conditions, PREG_SET_ORDER);
            preg_match_all('#]\s*(&&|\|\||AND|OR)#imU', $statement->condition, $operators, PREG_SET_ORDER);

            $conditions = array_filter($conditions);
            $operators = array_filter($operators);

            $operators = array_map(function (array $match) {
                return $match[1];
            }, $operators);

            $conditions = array_map(function (array $match) {
                return $match[1];
            }, $conditions);

            $newConditions = [];
            $applied = false;
            if (is_array($conditions)) {
                foreach ($conditions as $condition) {
                    foreach ($this->conditionMatchers as $conditionMatcher) {
                        $condition = trim($condition);
                        if ($conditionMatcher->shouldApply($condition)) {
                            $changedCondition = $conditionMatcher->change($condition);
                            $applied = true;
                            if (null !== $changedCondition) {
                                $newConditions[] = $changedCondition;
                            }
                        }
                    }
                }
            }

            if (! $applied) {
                return;
            }

            if ([] === $newConditions) {
                $statement->condition = '';
                return;
            }

            if (1 === count($newConditions)) {
                $statement->condition = sprintf('[%s]', $newConditions[0]);

                return;
            }

            if (0 === count($operators)) {
                $statement->condition = sprintf('[%s]', implode(' || ', $newConditions));

                return;
            }

            if (count($operators) !== (count($newConditions) - 1)) {
                throw new LogicException(
                    'The count of operators must be exactly one less than the count of conditions'
                );
            }

            array_unshift($operators, '');

            $newCondition = '';
            foreach ($newConditions as $key => $value) {
                $operator = $operators[$key];
                if ('' === $operator) {
                    $newCondition .= $value;
                    continue;
                }

                $newCondition .= sprintf(' %s %s', $operator, $value);
            }

            $statement->condition = sprintf('[%s]', $newCondition);
        }
    }
}
