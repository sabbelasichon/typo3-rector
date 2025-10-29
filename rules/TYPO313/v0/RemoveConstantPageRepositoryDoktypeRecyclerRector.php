<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\ClassConstFetch;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-101137-PageDoktypeRecyclerRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector\RemoveConstantPageRepositoryDoktypeRecyclerRectorTest
 */
final class RemoveConstantPageRepositoryDoktypeRecyclerRector extends AbstractRector implements DocumentedRuleInterface
{
    private const TARGET_CONSTANT_NAME = 'DOKTYPE_RECYCLER';

    private const PAGE_REPOSITORY_CLASS = 'TYPO3\CMS\Core\Domain\Repository\PageRepository';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove the constant `' . self::PAGE_REPOSITORY_CLASS . '::DOKTYPE_RECYCLER` and its usage in arrays and binary operations (||, &&)',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$excludeDoktypes = [
    \TYPO3\CMS\Core\Domain\Repository\PageRepository::DOKTYPE_RECYCLER,
    \TYPO3\CMS\Core\Domain\Repository\PageRepository::DOKTYPE_SYSFOLDER,
];
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$excludeDoktypes = [
    \TYPO3\CMS\Core\Domain\Repository\PageRepository::DOKTYPE_SYSFOLDER,
];
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
        return [Array_::class, BooleanOr::class, BooleanAnd::class];
    }

    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Array_) {
            return $this->refactorArray($node);
        }

        if ($node instanceof BooleanOr) {
            return $this->refactorBooleanOr($node);
        }

        if ($node instanceof BooleanAnd) {
            return $this->refactorBooleanAnd($node);
        }

        return null;
    }

    private function isTargetClassConstFetch(Node $node): bool
    {
        if (! $node instanceof ClassConstFetch) {
            return false;
        }

        if (! $this->isName($node->name, self::TARGET_CONSTANT_NAME)) {
            return false;
        }

        return $this->isObjectType($node->class, new ObjectType(self::PAGE_REPOSITORY_CLASS));
    }

    private function isComparisonWithTargetConstant(Node $node): bool
    {
        if (! $node instanceof Identical && ! $node instanceof Equal) {
            return false;
        }

        return $this->isTargetClassConstFetch($node->left) || $this->isTargetClassConstFetch($node->right);
    }

    private function refactorArray(Array_ $arrayNode): ?Array_
    {
        $hasChanged = false;
        $newItems = [];
        foreach ($arrayNode->items as $item) {
            if (! $item instanceof ArrayItem) {
                $newItems[] = $item;
                continue;
            }

            $isKeyTarget = $item->key instanceof Expr && $this->isTargetClassConstFetch($item->key);
            $isValueTarget = $this->isTargetClassConstFetch($item->value);

            if ($isKeyTarget || $isValueTarget) {
                $hasChanged = true;
            } else {
                $newItems[] = $item;
            }
        }

        if ($hasChanged) {
            $arrayNode->items = $newItems;
            return $arrayNode;
        }

        return null;
    }

    private function refactorBooleanOr(BooleanOr $booleanOrNode): ?Expr
    {
        $leftIsTargetComparison = $this->isComparisonWithTargetConstant($booleanOrNode->left);
        $rightIsTargetComparison = $this->isComparisonWithTargetConstant($booleanOrNode->right);

        if ($leftIsTargetComparison && $rightIsTargetComparison) {
            // (RECYCLER_COND_A || RECYCLER_COND_B)
            // If RECYCLER_COND implies 'false': (false || false) => false.
            // Could return $this->nodeFactory->createFalse();
            // For now, no change if both sides are targets to avoid complex transformations
            // without explicit test cases for this specific scenario.
            return null;
        }

        if ($leftIsTargetComparison) {
            // (RECYCLER_CONDITION) || some_other_condition => some_other_condition
            return $booleanOrNode->right;
        }

        if ($rightIsTargetComparison) {
            // some_other_condition || (RECYCLER_CONDITION) => some_other_condition
            return $booleanOrNode->left;
        }

        return null;
    }

    private function refactorBooleanAnd(BooleanAnd $booleanAndNode): ?Expr
    {
        $leftIsTargetComparison = $this->isComparisonWithTargetConstant($booleanAndNode->left);
        $rightIsTargetComparison = $this->isComparisonWithTargetConstant($booleanAndNode->right);

        if ($leftIsTargetComparison && $rightIsTargetComparison) {
            // (RECYCLER_COND_A && RECYCLER_COND_B)
            // If RECYCLER_COND implies 'true' for AND context: (true && true) => true.
            // Could return $this->nodeFactory->createTrue();
            // For now, no change if both sides are targets.
            return null;
        }

        if ($leftIsTargetComparison) {
            // (RECYCLER_CONDITION (true)) && some_other_condition => some_other_condition
            return $booleanAndNode->right;
        }

        if ($rightIsTargetComparison) {
            // some_other_condition && (RECYCLER_CONDITION (true)) => some_other_condition
            return $booleanAndNode->left;
        }

        return null;
    }
}
