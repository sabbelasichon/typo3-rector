<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v5;

use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://review.typo3.org/c/Packages/TYPO3.CMS/+/72056
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v5\SimplifyCheckboxItemsTCARector\SimplifyCheckboxItemsTCARectorTest
 */
final class SimplifyCheckboxItemsTCARector extends AbstractTcaRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Simplify checkbox items TCA', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'enabled' => [
            'label' => 'enabled',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 1,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ],
                ],
            ],
        ],
        'hidden' => [
            'label' => 'hidden',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true,
                    ],
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
        'enabled' => [
            'label' => 'enabled',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 1,
            ],
        ],
        'hidden' => [
            'label' => 'hidden',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
    ],
];
CODE_SAMPLE
        )]);
    }

    protected function refactorColumn(Expr $columnName, Expr $columnTca): void
    {
        $configArray = $this->extractSubArrayByKey($columnTca, self::CONFIG);
        if (! $configArray instanceof Array_) {
            return;
        }

        if (! $this->isConfigType($configArray, 'check')) {
            return;
        }

        if (! $this->configIsOfRenderType($configArray, 'checkboxToggle')) {
            return;
        }

        if (! $this->hasKey($configArray, 'items')) {
            return;
        }

        $itemsArrayItem = $this->extractArrayItemByKey($configArray, 'items');
        if (! $itemsArrayItem instanceof ArrayItem) {
            return;
        }

        /** @var Array_ $itemsArray */
        $itemsArray = $itemsArrayItem->value;

        $firstArrayItem = $itemsArray->items[0] ?? null;
        if (! $firstArrayItem instanceof ArrayItem) {
            return;
        }

        /** @var Array_ $firstItemsArray */
        $firstItemsArray = $firstArrayItem->value;

        if ($this->hasKey($firstItemsArray, 'invertStateDisplay')
            || $this->hasKey($firstItemsArray, 'labelChecked')
            || $this->hasKey($firstItemsArray, 'labelUnchecked')
            || $this->hasKey($firstItemsArray, 'iconIdentifierChecked')
            || $this->hasKey($firstItemsArray, 'iconIdentifierUnchecked')
        ) {
            // check for already migrated label/value keys
            $labelKeyArrayItem = $this->extractArrayItemByKey($firstItemsArray, 'label');
            if ($labelKeyArrayItem instanceof ArrayItem) {
                // Skip migrated items which have a "label" key that comes from MigrateItemsIndexedKeysToAssociativeRector.
                // only remove "value" if it exists
                $this->removeArrayItemFromArrayByKey($firstItemsArray, 'value');
                return;
            }

            // Remove array key 1
            $this->removeArrayItemFromArrayByKey($firstItemsArray, 1);
            if ($this->hasAstBeenChanged === false
                && isset($firstItemsArray->items[1])
                && $firstItemsArray->items[1] instanceof ArrayItem
            ) {
                $key = $firstItemsArray->items[1]->key;
                $keep = [
                    'invertStateDisplay',
                    'labelChecked',
                    'labelUnchecked',
                    'iconIdentifierChecked',
                    'iconIdentifierUnchecked',
                ];
                if (! $key instanceof String_ || ! in_array($key->value, $keep, true)) {
                    $this->removeArrayItemFromArrayByIndex($firstItemsArray, 1);
                }
            }

            return;
        }

        $itemsCount = count($firstItemsArray->items);
        if ($itemsCount > 1) {
            // we have an item without any keys (['label', 'value'])
            foreach ($firstItemsArray->items as $i => $arrayItem) {
                if ($arrayItem instanceof ArrayItem) {
                    // add a numbered key
                    $arrayItem->key = new Int_($i);
                    $this->hasAstBeenChanged = true;
                }
            }
        }

        // Check if array key 0 has a label
        $zeroKeyArrayItem = $this->extractArrayItemByKey($firstItemsArray, 0);
        if ($zeroKeyArrayItem instanceof ArrayItem && ! $this->valueResolver->isValue($zeroKeyArrayItem->value, '')) {
            $this->removeArrayItemFromArrayByKey($firstItemsArray, 1);
            return;
        }

        $labelKeyArrayItem = $this->extractArrayItemByKey($firstItemsArray, 'label');
        if ($labelKeyArrayItem instanceof ArrayItem) {
            // Skip migrated items which have a "label" key that comes from MigrateItemsIndexedKeysToAssociativeRector.
            return;
        }

        // Remove the whole items array
        $this->removeArrayItemFromArrayByKey($configArray, 'items');
    }
}
