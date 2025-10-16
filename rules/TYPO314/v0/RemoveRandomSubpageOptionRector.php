<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-107654-RemoveRandomSubpageOptionOfDoktypeShortcut.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\RemoveRandomSubpageOptionRector\RemoveRandomSubpageOptionRectorTest
 */
final class RemoveRandomSubpageOptionRector extends AbstractRector implements DocumentedRuleInterface
{
    private const TARGET_CONSTANT_NAME = 'SHORTCUT_MODE_RANDOM_SUBPAGE';

    private const PAGE_REPOSITORY_CLASS = 'TYPO3\CMS\Core\Domain\Repository\PageRepository';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove the constant `' . self::PAGE_REPOSITORY_CLASS . '::SHORTCUT_MODE_RANDOM_SUBPAGE` and its usage in arrays and binary operations (||, &&)',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$excludeDoktypes = [
    \TYPO3\CMS\Core\Domain\Repository\PageRepository::SHORTCUT_MODE_RANDOM_SUBPAGE,
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
                new CodeSample(
                    <<<'CODE_SAMPLE'
$page = $pageRepository->resolveShortcutPage($page, false, true);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$page = $pageRepository->resolveShortcutPage($page, true);
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
        return [Array_::class, BooleanOr::class, BooleanAnd::class, MethodCall::class];
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

        if ($node instanceof MethodCall) {
            return $this->refactorMethodCall($node);
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
            // (SHORTCUT_MODE_COND_A || SHORTCUT_MODE_COND_B)
            // If SHORTCUT_MODE_COND implies 'false': (false || false) => false.
            // Could return $this->nodeFactory->createFalse();
            // For now, no change if both sides are targets to avoid complex transformations
            // without explicit test cases for this specific scenario.
            return null;
        }

        if ($leftIsTargetComparison) {
            // (SHORTCUT_MODE_CONDITION) || some_other_condition => some_other_condition
            return $booleanOrNode->right;
        }

        if ($rightIsTargetComparison) {
            // some_other_condition || (SHORTCUT_MODE_CONDITION) => some_other_condition
            return $booleanOrNode->left;
        }

        return null;
    }

    private function refactorBooleanAnd(BooleanAnd $booleanAndNode): ?Expr
    {
        $leftIsTargetComparison = $this->isComparisonWithTargetConstant($booleanAndNode->left);
        $rightIsTargetComparison = $this->isComparisonWithTargetConstant($booleanAndNode->right);

        if ($leftIsTargetComparison && $rightIsTargetComparison) {
            // (SHORTCUT_MODE_COND_A && SHORTCUT_MODE_COND_B)
            // If SHORTCUT_MODE_COND implies 'true' for AND context: (true && true) => true.
            // Could return $this->nodeFactory->createTrue();
            // For now, no change if both sides are targets.
            return null;
        }

        if ($leftIsTargetComparison) {
            // (SHORTCUT_MODE_CONDITION (true)) && some_other_condition => some_other_condition
            return $booleanAndNode->right;
        }

        if ($rightIsTargetComparison) {
            // some_other_condition && (SHORTCUT_MODE_CONDITION (true)) => some_other_condition
            return $booleanAndNode->left;
        }

        return null;
    }

    private function refactorMethodCall(MethodCall $node): ?Expr
    {
        if (! $this->isObjectType($node->var, new ObjectType(self::PAGE_REPOSITORY_CLASS))) {
            return null;
        }

        if (! $this->isName($node->name, 'resolveShortcutPage')) {
            return null;
        }

        if (! isset($node->args[1])) {
            return null;
        }

        unset($node->args[1]);

        $node->args = array_values($node->args);

        return $node;
    }
}
