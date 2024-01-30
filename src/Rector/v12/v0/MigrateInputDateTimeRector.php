<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\Helper\ArrayUtility;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97232-NewTCATypeDatetime.html
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-97358-RemovedEvalintFromTCATypeDatetime.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\tca\MigrateInputDateTimeRector\MigrateInputDateTimeRectorTest
 */
final class MigrateInputDateTimeRector extends AbstractTcaRector
{
    /**
     * @var string
     */
    private const INPUT_DATE_TIME = 'inputDateTime';

    /**
     * @var string[]
     */
    private const DATETIME_TYPES = ['date', 'datetime', 'time'];

    /**
     * @var array<string, array<string, string>>
     */
    private const DATETIME_EMPTY_VALUES = [
        'date' => [
            'empty' => '0000-00-00',
        ],
        'datetime' => [
            'empty' => '0000-00-00 00:00:00',
        ],
        'time' => [
            'empty' => '00:00:00',
        ],
    ];

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate renderType inputDateTime to new TCA type datetime', [new CodeSample(
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'a_datetime_field' => [
             'label' => 'Datetime field',
             'config' => [
                 'type' => 'input',
                 'renderType' => 'inputDateTime',
                 'required' => true,
                 'size' => 20,
                 'max' => 1024,
                 'eval' => 'date,int',
                 'default' => 0,
             ],
        ],
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'columns' => [
        'a_datetime_field' => [
             'label' => 'Datetime field',
             'config' => [
                 'type' => 'datetime',
                 'format' => 'date',
                 'required' => true,
                 'size' => 20,
                 'default' => 0,
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
        if (! $this->isConfigType($configArray, 'input')
            || ! $this->configIsOfRenderType($configArray, self::INPUT_DATE_TIME)
        ) {
            return;
        }

        // Set the TCA type to "datetime"
        $this->changeTcaType($configArray, 'datetime');

        // Remove "renderType" and "max"
        // Note: Also remove "format". This option had been documented but was actually
        //       never used in the FormEngine element. This migration will set it according
        //       to the corresponding "eval" value.
        $this->removeArrayItemFromArrayByKey($configArray, 'renderType');
        $this->removeArrayItemFromArrayByKey($configArray, 'max');
        $this->removeArrayItemFromArrayByKey($configArray, 'format');

        $evalList = [];
        $evalArrayItem = $this->extractArrayItemByKey($configArray, 'eval');
        if ($evalArrayItem instanceof ArrayItem) {
            $evalString = $this->valueResolver->getValue($evalArrayItem->value);
            if (! is_string($evalString)) {
                return;
            }

            $evalList = ArrayUtility::trimExplode(',', $evalString, true);

            // Remove 'eval' config
            $this->removeArrayItemFromArrayByKey($configArray, 'eval');
        }

        // Set the "format" based on "eval"
        // If no 'format' config is set it will fall back to 'datetime'
        if ($evalList !== []) {
            if (in_array('date', $evalList, true)) {
                $configArray->items[] = new ArrayItem(new String_('date'), new String_('format'));
            } elseif (in_array('time', $evalList, true)) {
                $configArray->items[] = new ArrayItem(new String_('time'), new String_('format'));
            } elseif (in_array('timesec', $evalList, true)) {
                $configArray->items[] = new ArrayItem(new String_('timesec'), new String_('format'));
            }
        }

        if (in_array('null', $evalList, true)) {
            // Set "eval" to "null", since it's currently defined and the only allowed "eval" for type=datetime
            $configArray->items[] = new ArrayItem(new String_('null'), new String_('eval'));
        } else {
            $this->removeArrayItemFromArrayByKey($configArray, 'eval');
        }

        // Removes option [config][default], if the default is the native "empty" value

        $defaultArrayItem = $this->extractArrayItemByKey($configArray, 'default');
        if ($defaultArrayItem instanceof ArrayItem) {
            $dbTypeValue = null;
            $dbTypeArrayItem = $this->extractArrayItemByKey($configArray, 'dbType');
            if ($dbTypeArrayItem instanceof ArrayItem) {
                $dbTypeValue = $this->valueResolver->getValue($dbTypeArrayItem->value);
            }

            $defaultValue = $this->valueResolver->getValue($defaultArrayItem->value);
            if (in_array($dbTypeValue, self::DATETIME_TYPES, true)) {
                if ($defaultValue === self::DATETIME_EMPTY_VALUES[$dbTypeValue]['empty']) {
                    // Remove default for native datetime fields if the default is the native "empty" value
                    $this->removeArrayItemFromArrayByKey($configArray, 'default');
                }

                $this->hasAstBeenChanged = true;
                return;
            }

            if (is_int($defaultValue)) {
                $this->hasAstBeenChanged = true;
                return;
            }

            if ($defaultValue === '') {
                // Always use int as default (string values are no longer supported for "datetime")
                $defaultArrayItem->value = new LNumber(0);
                $this->hasAstBeenChanged = true;
                return;
            }

            if (self::canBeInterpretedAsInteger($defaultValue)) {
                // Cast default to int, in case it can be interpreted as integer
                $defaultArrayItem->value = new LNumber((int) $defaultValue);
                $this->hasAstBeenChanged = true;
                return;
            }

            if ($defaultArrayItem->value instanceof FuncCall
                && $this->getName($defaultArrayItem->value) === 'time'
            ) {
                $this->hasAstBeenChanged = true;
                return;
            }

            // Unset default in case it's a no longer supported string
            $this->removeArrayItemFromArrayByKey($configArray, 'default');
        }

        $this->hasAstBeenChanged = true;
    }

    /**
     * @param mixed $var
     */
    private static function canBeInterpretedAsInteger($var): bool
    {
        if ($var === '' || is_object($var) || is_array($var)) {
            return false;
        }

        return (string) (int) $var === (string) $var;
    }
}
