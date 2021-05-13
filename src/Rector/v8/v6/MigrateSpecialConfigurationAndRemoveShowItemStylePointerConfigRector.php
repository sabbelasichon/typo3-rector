<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v6;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\Helper\ArrayUtility;
use Ssch\TYPO3Rector\Rector\Tca\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.6/Deprecation-79440-TcaChanges.html
 */
final class MigrateSpecialConfigurationAndRemoveShowItemStylePointerConfigRector extends AbstractTcaRector
{
    /**
     * @var array<string, string>
     */
    private $defaultExtrasFromColumns = [];

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Move special configuration to columns overrides', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'types' => [
        0 => [
            'showitem' => 'aField,anotherField;with;;nowrap,thirdField',
        ],
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'types' => [
        0 => [
            'showitem' => 'aField,anotherField;with,thirdField',
            'columnsOverrides' => [
                'anotherField' => [
                    'config' => [
                        'wrap' => 'off',
                    ]
                ],
            ],
        ],
    ],
];
CODE_SAMPLE
        )]);
    }

    protected function resetInnerState(): void
    {
        $this->defaultExtrasFromColumns = [];
    }

    protected function refactorColumn(Expr $columnName, Expr $columnTca): void
    {
        if (! $columnTca instanceof Array_) {
            return;
        }

        $defaultExtras = $this->extractArrayValueByKey($columnTca, 'defaultExtras');
        if (null === $defaultExtras) {
            return;
        }

        $this->defaultExtrasFromColumns[$this->valueResolver->getValue($columnName)] = $this->valueResolver->getValue(
            $defaultExtras
        );
    }

    protected function refactorTypes(Array_ $types): void
    {
        foreach ($types->items as $typeArrayItem) {
            if (! $typeArrayItem instanceof ArrayItem) {
                continue;
            }

            if (! $typeArrayItem->value instanceof Array_) {
                continue;
            }

            $typeConfiguration = $typeArrayItem->value;
            $showitemNode = $this->extractArrayValueByKey($typeConfiguration, 'showitem');

            if (! $showitemNode instanceof String_) {
                continue;
            }

            $showitem = $this->valueResolver->getValue($showitemNode);
            if (! is_string($showitem)) {
                continue;
            }

            if (false === strpos($showitem, ';')) {
                // Continue directly if no semicolon is found
                continue;
            }
            $itemList = explode(',', $showitem);
            $newFieldStrings = [];
            foreach ($itemList as $fieldString) {
                $fieldString = rtrim($fieldString, ';');
                // Unpack the field definition, migrate and remove as much as possible
                // Keep empty parameters in trimExplode here (third parameter FALSE), so position is not changed
                $fieldArray = ArrayUtility::trimExplode(';', $fieldString);
                $fieldArray = [
                    'fieldName' => isset($fieldArray[0]) ? $fieldArray[0] : '',
                    'fieldLabel' => isset($fieldArray[1]) ? $fieldArray[1] : null,
                    'paletteName' => isset($fieldArray[2]) ? $fieldArray[2] : null,
                    'fieldExtra' => isset($fieldArray[3]) ? $fieldArray[3] : null,
                ];
                $fieldName = $fieldArray['fieldName'];
                if (null !== $fieldArray['fieldExtra']) {
                    // Move fieldExtra "specConf" to columnsOverrides "defaultExtras"
                    // Merge with given defaultExtras from columns.
                    // They will be the first part of the string, so if "specConf" from types changes the same settings,
                    // those will override settings from defaultExtras of columns
                    $newDefaultExtras = [];
                    if (isset($this->defaultExtrasFromColumns[$fieldName])) {
                        $newDefaultExtras[] = $this->defaultExtrasFromColumns[$fieldName];
                    }

                    $newDefaultExtras[] = $fieldArray['fieldExtra'];
                    $newDefaultExtras = implode(':', $newDefaultExtras);
                    if ('' !== $newDefaultExtras) {
                        $columnsOverrides = $this->extractSubArrayByKey($typeConfiguration, 'columnsOverrides');
                        if (null === $columnsOverrides) {
                            $columnsOverrides = new Array_([]);
                            $typeConfiguration->items[] = new ArrayItem($columnsOverrides, new String_(
                                'columnsOverrides'
                            ));
                        }

                        $columnOverride = $this->extractSubArrayByKey($columnsOverrides, $fieldName);
                        if (null === $columnOverride) {
                            $columnOverride = new Array_([]);
                            $columnsOverrides->items[] = new ArrayItem($columnOverride, new String_($fieldName));
                        }
                        $columnOverride->items[] = new ArrayItem(new String_($newDefaultExtras), new String_(
                            'defaultExtras'
                        ));
                        $this->hasAstBeenChanged = true;
                    }
                }

                unset($fieldArray['fieldExtra']);
                if (3 === count($fieldArray) && empty($fieldArray['paletteName'])) {
                    unset($fieldArray['paletteName']);
                }
                if (2 === count($fieldArray) && empty($fieldArray['fieldLabel'])) {
                    unset($fieldArray['fieldLabel']);
                }
                if (1 === count($fieldArray) && empty($fieldArray['fieldName'])) {
                    // The field may vanish if nothing is left
                    unset($fieldArray['fieldName']);
                }
                $newFieldString = implode(';', $fieldArray);
                if (! empty($newFieldString)) {
                    $newFieldStrings[] = $newFieldString;
                }
            }
            $showitemNode->value = implode(',', $newFieldStrings);
        }
    }
}
