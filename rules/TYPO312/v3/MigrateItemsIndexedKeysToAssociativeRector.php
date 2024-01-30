<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v3;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.3/Deprecation-99739-IndexedArrayKeysForTCAItems.html
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.3/Feature-99739-AssociativeArrayKeysForTCAItems.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v3\tca\MigrateItemsIndexedKeysToAssociativeRector\MigrateItemsIndexedKeysToAssociativeRectorTest
 */
final class MigrateItemsIndexedKeysToAssociativeRector extends AbstractTcaRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrates indexed item array keys to associative for type select, radio and check', [
            new CodeSample(
                <<<'CODE_SAMPLE'
return [
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectCheckBox',
                'items' => [
                    ['My label', 0, 'my-icon', 'group1', 'My Description'],
                    ['My label 1', 1, 'my-icon', 'group1', 'My Description'],
                    ['My label 2', 2, 'my-icon', 'group1', 'My Description'],
                ],
            ],
        ],
    ],
];
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
return [
    'columns' => [
        'aColumn' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectCheckBox',
                'items' => [
                    ['label' => 'My label', 'value' => 0, 'icon' => 'my-icon', 'group' => 'group1', 'description' => 'My Description'],
                    ['label' => 'My label 1', 'value' => 1, 'icon' => 'my-icon', 'group' => 'group1', 'description' => 'My Description'],
                    ['label' => 'My label 2', 'value' => 2, 'icon' => 'my-icon', 'group' => 'group1', 'description' => 'My Description'],
                ],
            ],
        ],
    ],
];
CODE_SAMPLE
            ),
        ]);
    }

    protected function refactorColumn(Expr $columnName, Expr $columnTca): void
    {
        $configArray = $this->extractSubArrayByKey($columnTca, self::CONFIG);
        if (! $configArray instanceof Array_) {
            return;
        }

        if (
            ! $this->isConfigType($configArray, 'select')
            && ! $this->isConfigType($configArray, 'radio')
            && ! $this->isConfigType($configArray, 'check')
        ) {
            return;
        }

        $exprArrayItemToChange = $this->extractArrayItemByKey($configArray, 'items');
        if (! $exprArrayItemToChange instanceof ArrayItem) {
            return;
        }

        if (! $exprArrayItemToChange->value instanceof Array_) {
            return;
        }

        foreach ($exprArrayItemToChange->value->items as $exprArrayItem) {
            if (! $exprArrayItem instanceof ArrayItem) {
                continue;
            }

            if (! $exprArrayItem->value instanceof Array_) {
                continue;
            }

            if (array_key_exists(
                0,
                $exprArrayItem->value->items
            ) && $exprArrayItem->value->items[0] instanceof ArrayItem) {
                $exprArrayItem->value->items[0]->key = new String_('label');
                $this->hasAstBeenChanged = true;
            }

            if (! $this->isConfigType($configArray, 'check') && array_key_exists(
                1,
                $exprArrayItem->value->items
            ) && $exprArrayItem->value->items[1] instanceof ArrayItem) {
                $exprArrayItem->value->items[1]->key = new String_('value');
                $this->hasAstBeenChanged = true;
            }

            if ($this->isConfigType($configArray, 'select')) {
                if (array_key_exists(
                    2,
                    $exprArrayItem->value->items
                ) && $exprArrayItem->value->items[2] instanceof ArrayItem) {
                    $exprArrayItem->value->items[2]->key = new String_('icon');
                    $this->hasAstBeenChanged = true;
                }

                if (array_key_exists(
                    3,
                    $exprArrayItem->value->items
                ) && $exprArrayItem->value->items[3] instanceof ArrayItem) {
                    $exprArrayItem->value->items[3]->key = new String_('group');
                    $this->hasAstBeenChanged = true;
                }

                if (array_key_exists(
                    4,
                    $exprArrayItem->value->items
                ) && $exprArrayItem->value->items[4] instanceof ArrayItem) {
                    $exprArrayItem->value->items[4]->key = new String_('description');
                    $this->hasAstBeenChanged = true;
                }
            }
        }
    }
}
