<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\tca;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\Helper\ArrayUtility;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97271-NewTCATypeColor.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateRenderTypeColorpickerToTypeColorRector\MigrateRenderTypeColorpickerToTypeColorRectorTest
 */
final class MigrateRenderTypeColorpickerToTypeColorRector extends AbstractTcaRector
{
    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate renderType colorpicker to type color', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'a_color_field' => [
            'label' => 'Color field',
            'config' => [
                'type' => 'input',
                'renderType' => 'colorpicker',
                'required' => true,
                'size' => 20,
                'max' => 1024,
                'eval' => 'trim',
                'valuePicker' => [
                    'items' => [
                        ['typo3 orange', '#FF8700'],
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
        'a_color_field' => [
            'label' => 'Color field',
            'config' => [
                'type' => 'color',
                'required' => true,
                'size' => 20,
                'valuePicker' => [
                    'items' => [
                        ['typo3 orange', '#FF8700'],
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

        // Early return in case column is not of type=input with renderType=inputDateTime
        if (! $this->isConfigType($configArray, 'input')) {
            return;
        }

        if (! $this->configIsOfRenderType($configArray, 'colorpicker')) {
            return;
        }

        // Set the TCA type to "color"
        $this->changeTcaType($configArray, 'color');

        // Remove 'max' config
        $this->hasAstBeenChanged = $this->removeArrayItemFromArrayByKey($configArray, 'max');

        // Remove 'renderType' config
        $this->hasAstBeenChanged = $this->removeArrayItemFromArrayByKey($configArray, 'renderType');

        $evalArrayItem = $this->extractArrayItemByKey($configArray, 'eval');
        if (! $evalArrayItem instanceof ArrayItem) {
            return;
        }

        $evalListValue = $this->valueResolver->getValue($evalArrayItem->value);
        if (! is_string($evalListValue)) {
            return;
        }

        $evalList = ArrayUtility::trimExplode(',', $evalListValue, true);

        if (in_array('null', $evalList, true)) {
            // Set "eval" to "null", since it's currently defined and the only allowed "eval" for type=color
            $evalArrayItem->value = new String_('null');
        } else {
            // 'eval' is empty, remove whole configuration
            $this->removeArrayItemFromArrayByKey($configArray, 'eval');
        }

        $this->hasAstBeenChanged = true;
    }
}
