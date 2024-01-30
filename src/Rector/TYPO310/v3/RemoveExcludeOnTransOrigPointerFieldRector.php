<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\TYPO310\v3;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Stmt\Return_;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.3/Important-89672-TransOrigPointerFieldIsNotLongerAllowedToBeExcluded.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v3\RemoveExcludeOnTransOrigPointerFieldRector\RemoveExcludeOnTransOrigPointerFieldRectorTest
 */
final class RemoveExcludeOnTransOrigPointerFieldRector extends AbstractTcaRector
{
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

        $ctrlItems = $ctrlArrayItem->value;
        if (! $ctrlItems instanceof Array_) {
            return null;
        }

        $columnsArrayItem = $this->extractColumns($node);
        if (! $columnsArrayItem instanceof ArrayItem) {
            return null;
        }

        $columnItems = $columnsArrayItem->value;

        if (! $columnItems instanceof Array_) {
            return null;
        }

        $transOrigPointerField = null;
        foreach ($ctrlItems->items as $fieldValue) {
            if (! $fieldValue instanceof ArrayItem) {
                continue;
            }

            if (! $fieldValue->key instanceof Expr) {
                continue;
            }

            if ($this->valueResolver->isValue($fieldValue->key, 'transOrigPointerField')) {
                $transOrigPointerField = $this->valueResolver->getValue($fieldValue->value);
                break;
            }
        }

        if ($transOrigPointerField === null) {
            return null;
        }

        $hasAstBeenChanged = false;
        foreach ($columnItems->items as $columnItem) {
            if (! $columnItem instanceof ArrayItem) {
                continue;
            }

            if (! $columnItem->key instanceof Expr) {
                continue;
            }

            $fieldName = $this->valueResolver->getValue($columnItem->key);

            if ($fieldName !== $transOrigPointerField) {
                continue;
            }

            if (! $columnItem->value instanceof Array_) {
                continue;
            }

            if ($this->removeArrayItemFromArrayByKey($columnItem->value, 'exclude')) {
                $hasAstBeenChanged = true;
            }
        }

        return $hasAstBeenChanged ? $node : null;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('transOrigPointerField is not longer allowed to be excluded', [
            new CodeSample(
                <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'transOrigPointerField' => 'l10n_parent',
    ],
    'columns' => [
        'l10n_parent' => [
            'exclude' => true,
            'config' => [
                'type' => 'select',
            ],
        ],
    ],
];
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
return [
    'ctrl' => [
        'transOrigPointerField' => 'l10n_parent',
    ],
    'columns' => [
        'l10n_parent' => [
            'config' => [
                'type' => 'select',
            ],
        ],
    ],
];
CODE_SAMPLE
            ),
        ]);
    }
}
