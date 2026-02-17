<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v3;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://github.com/TYPO3/Fluid/blob/main/CHANGELOG.md
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateEvaluateConditionToVerdictInAbstractConditionViewHelperRector\MigrateEvaluateConditionToVerdictInAbstractConditionViewHelperRectorTest
 */
final class MigrateEvaluateConditionToVerdictInAbstractConditionViewHelperRector extends AbstractRector implements DocumentedRuleInterface
{
    private const ABSTRACT_CONDITION_VIEW_HELPER_CLASSES = [
        'TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper',
        'TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Migrate evaluateCondition() to verdict() in AbstractConditionViewHelper subclasses',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class MyConditionViewHelper extends AbstractConditionViewHelper
{
    protected static function evaluateCondition($arguments = null): bool
    {
        return (bool)$arguments['condition'];
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

class MyConditionViewHelper extends AbstractConditionViewHelper
{
    public static function verdict(array $arguments, RenderingContextInterface $renderingContext): bool
    {
        return (bool)$arguments['condition'];
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->extends instanceof Name) {
            return null;
        }

        if (! $this->isNames($node->extends, self::ABSTRACT_CONDITION_VIEW_HELPER_CLASSES)) {
            return null;
        }

        $evaluateConditionMethod = $node->getMethod('evaluateCondition');
        if (! $evaluateConditionMethod instanceof ClassMethod) {
            return null;
        }

        // Check if verdict() method already exists
        $verdictMethod = $node->getMethod('verdict');
        if ($verdictMethod instanceof ClassMethod) {
            // verdict() already exists, just remove evaluateCondition()
            foreach ($node->stmts as $key => $stmt) {
                if ($stmt === $evaluateConditionMethod) {
                    unset($node->stmts[$key]);
                    return $node;
                }
            }

            return null;
        }

        // Migrate evaluateCondition() to verdict()
        $this->migrateEvaluateConditionToVerdict($evaluateConditionMethod);

        return $node;
    }

    private function migrateEvaluateConditionToVerdict(ClassMethod $method): void
    {
        // Rename method
        $method->name = new Identifier('verdict');

        // Change visibility from protected to public
        $method->flags = ($method->flags & ~Modifiers::PROTECTED) | Modifiers::PUBLIC;

        // Update first parameter: $arguments = null -> array $arguments
        if (isset($method->params[0])) {
            $method->params[0]->type = new Identifier('array');
            $method->params[0]->default = null;
        } else {
            $method->params[0] = new Param(new Variable('arguments'), null, new Identifier('array'));
        }

        // Add second parameter: RenderingContextInterface $renderingContext
        if (! isset($method->params[1])) {
            $method->params[1] = new Param(
                new Variable('renderingContext'),
                null,
                new FullyQualified('TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface')
            );
        }

        // Ensure return type is bool
        $method->returnType = new Identifier('bool');
    }
}
