<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v6;

use Nette\Utils\Strings;
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
 * @see \Ssch\TYPO3Rector\Tests\Rector\v8\v6\MigrateSpecialConfigurationAndRemoveShowItemStylePointerConfigRector\MigrateSpecialConfigurationAndRemoveShowItemStylePointerConfigRectorTest
 */
final class MigrateSpecialConfigurationAndRemoveShowItemStylePointerConfigRector extends AbstractTcaRector
{
    /**
     * @var string
     */
    private const FIELD_NAME = 'fieldName';

    /**
     * @var string
     */
    private const FIELD_LABEL = 'fieldLabel';

    /**
     * @var string
     */
    private const PALETTE_NAME = 'paletteName';

    /**
     * @var string
     */
    private const FIELD_EXTRA = 'fieldExtra';

    /**
     * @var string
     */
    private const REPLACE_ALL_WHITESPACES = '/\s+/';

    /**
     * @var array<string, string>
     */
    private array $defaultExtrasFromColumns = [];

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
                    'defaultExtras' => 'nowrap',
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
        if (! $defaultExtras instanceof Expr) {
            return;
        }

        $this->defaultExtrasFromColumns[$this->valueResolver->getValue($columnName)] = $this->valueResolver->getValue(
            $defaultExtras
        );
    }

    protected function refactorType(Expr $typeKey, Expr $typeConfiguration): void
    {
        if (! $typeConfiguration instanceof Array_) {
            return;
        }

        $showitemNode = $this->extractArrayValueByKey($typeConfiguration, 'showitem');

        if (! $showitemNode instanceof String_) {
            return;
        }

        $showitem = $this->valueResolver->getValue($showitemNode);
        if (! is_string($showitem)) {
            return;
        }

        if (! str_contains($showitem, ';')) {
            // Continue directly if no semicolon is found
            return;
        }

        $itemList = explode(',', $showitem);
        $newFieldStrings = [];
        foreach ($itemList as $fieldString) {
            $fieldString = rtrim($fieldString, ';');
            // Unpack the field definition, migrate and remove as much as possible
            // Keep empty parameters in trimExplode here (third parameter FALSE), so position is not changed
            $fieldArray = ArrayUtility::trimExplode(';', $fieldString);
            $fieldArray = [
                self::FIELD_NAME => $fieldArray[0] ?? '',
                self::FIELD_LABEL => $fieldArray[1] ?? null,
                self::PALETTE_NAME => $fieldArray[2] ?? null,
                self::FIELD_EXTRA => $fieldArray[3] ?? null,
            ];

            $fieldName = $fieldArray[self::FIELD_NAME];

            if (null !== $fieldArray[self::FIELD_EXTRA]) {
                // Move fieldExtra "specConf" to columnsOverrides "defaultExtras"
                // Merge with given defaultExtras from columns.
                // They will be the first part of the string, so if "specConf" from types changes the same settings,
                // those will override settings from defaultExtras of columns
                $newDefaultExtras = [];
                if (isset($this->defaultExtrasFromColumns[$fieldName])) {
                    $newDefaultExtras[] = $this->defaultExtrasFromColumns[$fieldName];
                }

                $newDefaultExtras[] = $fieldArray[self::FIELD_EXTRA];
                $newDefaultExtrasString = trim(implode(':', $newDefaultExtras));
                if ('' !== $newDefaultExtrasString) {
                    $columnsOverridesArray = $this->extractSubArrayByKey($typeConfiguration, 'columnsOverrides');
                    if (! $columnsOverridesArray instanceof Array_) {
                        $columnsOverridesArray = new Array_([]);
                        $typeConfiguration->items[] = new ArrayItem($columnsOverridesArray, new String_(
                            'columnsOverrides'
                        ));
                    }

                    $columnOverrideArray = $this->extractSubArrayByKey($columnsOverridesArray, $fieldName);
                    if (! $columnOverrideArray instanceof Array_) {
                        $columnOverrideArray = new Array_([]);
                        $columnsOverridesArray->items[] = new ArrayItem($columnOverrideArray, new String_($fieldName));
                    }

                    $columnOverrideArray->items[] = new ArrayItem(new String_($newDefaultExtrasString), new String_(
                        'defaultExtras'
                    ));
                    $this->hasAstBeenChanged = true;
                }
            }

            unset($fieldArray[self::FIELD_EXTRA]);
            if (3 === count($fieldArray) && '' === ($fieldArray[self::PALETTE_NAME] ?? '')) {
                unset($fieldArray[self::PALETTE_NAME]);
            }

            if (2 === count($fieldArray) && '' === ($fieldArray[self::FIELD_LABEL] ?? '')) {
                unset($fieldArray[self::FIELD_LABEL]);
            }

            if (1 === count($fieldArray) && '' === $fieldArray[self::FIELD_NAME]) {
                // The field may vanish if nothing is left
                unset($fieldArray[self::FIELD_NAME]);
            }

            $newFieldString = implode(';', $fieldArray);
            if ('' !== $newFieldString) {
                $newFieldStrings[] = $newFieldString;
            }
        }

        $newValue = implode(',', $newFieldStrings);

        if (! $this->shouldSkip($showitemNode->value, $newValue)) {
            $showitemNode->value = $newValue;
        }
    }

    private function shouldSkip(string $oldValue, string $newValue): bool
    {
        $oldValueStripped = rtrim(Strings::replace($oldValue, self::REPLACE_ALL_WHITESPACES), ',');
        $newValueStripped = rtrim(Strings::replace($newValue, self::REPLACE_ALL_WHITESPACES), ',');

        return Strings::compare($oldValueStripped, $newValueStripped);
    }
}
