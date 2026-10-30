<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO310\v1;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\RuleDefinition;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.1/Feature-88907-AlwaysEnableFilterInSelectMultipleSideBySideFields.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v1\RemoveEnableMultiSelectFilterTextfieldRector\RemoveEnableMultiSelectFilterTextfieldRectorTest
 */
final class RemoveEnableMultiSelectFilterTextfieldRector extends AbstractTcaRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove `"enableMultiSelectFilterTextfield" => true` as its default from render type "selectMultipleSideBySide"',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
return [
    'columns' => [
        'foo' => [
            'label' => 'foo',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'enableMultiSelectFilterTextfield' => true,
            ],
        ],
    ],
];
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
return [
    'columns' => [
        'foo' => [
            'label' => 'foo',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
            ],
        ],
    ],
];
CODE_SAMPLE
                ),

            ]
        );
    }

    protected function refactorColumn(Expr $columnName, Expr $columnTca): void
    {
        $configArray = $this->extractSubArrayByKey($columnTca, self::CONFIG);
        if (! $configArray instanceof Array_) {
            return;
        }

        if (! $this->configIsOfRenderType($configArray, 'selectMultipleSideBySide')) {
            return;
        }

        $this->removeArrayItemFromArrayByKey($configArray, 'enableMultiSelectFilterTextfield');
    }
}
