<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v5;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/8.5/Breaking-78191-RemoveSupportForTransForeignTableInTCA.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v8\v5\RemoveSupportForTransForeignTableRector\RemoveSupportForTransForeignTableRectorTest
 */
final class RemoveSupportForTransForeignTableRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Return_::class];
    }

    /**
     * @param Return_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isFullTca($node)) {
            return null;
        }

        $ctrlArrayItem = $this->extractCtrl($node);
        if (! $ctrlArrayItem instanceof ArrayItem) {
            return null;
        }

        $items = $ctrlArrayItem->value;

        if (! $items instanceof Array_) {
            return null;
        }

        $hasAstBeenChanged = false;
        foreach ($items->items as $fieldKey => $fieldValue) {
            if (! $fieldValue instanceof ArrayItem) {
                continue;
            }

            if (! $fieldValue->key instanceof Expr) {
                continue;
            }

            if ($this->valueResolver->isValues($fieldValue->key, ['transForeignTable', 'transOrigPointerTable'])) {
                unset($items->items[$fieldKey]);
                $hasAstBeenChanged = true;
            }
        }

        return $hasAstBeenChanged ? $node : null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove support for transForeignTable in TCA', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'transForeignTable' => 'l10n_parent',
        'transOrigPointerTable' => 'l10n_parent',
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [],
];
CODE_SAMPLE
        )]);
    }
}
