<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v5;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://review.typo3.org/c/Packages/TYPO3.CMS/+/72056
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v5\tca\SimplifyCheckboxItemsTCARector\SimplifyCheckboxItemsTCARectorTest
 */
final class SimplifyCheckboxItemsTCARector extends AbstractTcaRector
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

        // Check if 'invertStateDisplay' is set
        if ($this->hasKey($firstItemsArray, 'invertStateDisplay')) {
            // Remove array key 1
            $this->removeArrayItemFromArrayByKey($firstItemsArray, 1);
            $this->hasAstBeenChanged = true;
            return;
        }

        // Check if array key 0 has a label
        $zeroKeyArrayItem = $this->extractArrayItemByKey($firstItemsArray, 0);
        if ($zeroKeyArrayItem instanceof ArrayItem && ! $this->valueResolver->isValue($zeroKeyArrayItem->value, '')) {
            $this->removeArrayItemFromArrayByKey($firstItemsArray, 1);
            $this->hasAstBeenChanged = true;
            return;
        }

        // Remove the whole items array
        $this->removeArrayItemFromArrayByKey($configArray, 'items');
        $this->hasAstBeenChanged = true;
    }
}
