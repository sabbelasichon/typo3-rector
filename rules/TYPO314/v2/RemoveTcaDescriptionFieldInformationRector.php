<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v2;

use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\RuleDefinition;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.2/Deprecation-109280-FormEngineTcaDescriptionFieldInformation.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v2\RemoveTcaDescriptionFieldInformationRector\RemoveTcaDescriptionFieldInformationRectorTest
 */
final class RemoveTcaDescriptionFieldInformationRector extends AbstractTcaRector implements DocumentedRuleInterface
{
    private const TCA_DESCRIPTION = 'tcaDescription';

    private const FIELD_INFORMATION = 'fieldInformation';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove TCA fieldInformation node tcaDescription', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'my_field' => [
            'label' => 'My field',
            'description' => 'Help text for this field',
            'config' => [
                'type' => 'input',
                'fieldInformation' => [
                    'tcaDescription' => [
                        'renderType' => 'tcaDescription',
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
        'my_field' => [
            'label' => 'My field',
            'description' => 'Help text for this field',
            'config' => [
                'type' => 'input',
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

        $fieldInformationArray = $this->extractSubArrayByKey($configArray, self::FIELD_INFORMATION);
        if (! $fieldInformationArray instanceof Array_) {
            return;
        }

        $hasRemovedItem = false;
        foreach ($fieldInformationArray->items as $index => $arrayItem) {
            if (! $arrayItem instanceof ArrayItem) {
                continue;
            }

            if (! $this->isTcaDescriptionNode($arrayItem)) {
                continue;
            }

            unset($fieldInformationArray->items[$index]);
            $hasRemovedItem = true;
            $this->hasAstBeenChanged = true;
        }

        if ($hasRemovedItem && $fieldInformationArray->items === []) {
            $this->removeArrayItemFromArrayByKey($configArray, self::FIELD_INFORMATION);
        }
    }

    protected function refactorType(Expr $typeKey, Expr $typeConfig): void
    {
        $columnsOverridesArray = $this->extractSubArrayByKey($typeConfig, 'columnsOverrides');
        if (! $columnsOverridesArray instanceof Array_) {
            return;
        }

        $this->refactorColumnList($columnsOverridesArray);
    }

    private function isTcaDescriptionNode(ArrayItem $arrayItem): bool
    {
        if (! $arrayItem->value instanceof Array_) {
            return false;
        }

        if ($this->configIsOfRenderType($arrayItem->value, self::TCA_DESCRIPTION)) {
            return true;
        }

        if (! $arrayItem->key instanceof Expr) {
            return false;
        }

        if (! $this->valueResolver->isValue($arrayItem->key, self::TCA_DESCRIPTION)) {
            return false;
        }

        return ! $this->hasRenderType($arrayItem->value);
    }
}
