<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;

/**
 * Base rector that detects Arrays containing TCA definitions and allows to refactor them
 */
abstract class AbstractTcaRector extends AbstractRector
{
    /**
     * @var string
     */
    protected const TYPE = 'type';

    /**
     * @var string
     */
    protected const CONFIG = 'config';

    /**
     * @var string
     */
    protected const LABEL = 'label';

    protected bool $hasAstBeenChanged = false;

    protected ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Array_::class];
    }

    /**
     * @param Array_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $this->resetInnerState();
        $this->hasAstBeenChanged = false;
        if ($this->isFullTcaDefinition($node)) {
            // we found a tca definition of a full table. Process it as a whole:
            $columnsArray = $this->extractSubArrayByKey($node, 'columns');
            if ($columnsArray instanceof Array_) {
                $this->refactorColumnList($columnsArray);
            }

            $typesArray = $this->extractSubArrayByKey($node, 'types');
            if ($typesArray instanceof Array_) {
                $this->refactorTypes($typesArray);
            }

            $ctrlArray = $this->extractSubArrayByKey($node, 'ctrl');
            if ($ctrlArray instanceof Array_) {
                $this->refactorCtrl($ctrlArray);
            }

            $interfaceArray = $this->extractSubArrayByKey($node, 'interface');
            if ($interfaceArray instanceof Array_) {
                $this->refactorInterface($interfaceArray, $node);
            }

            return $this->hasAstBeenChanged ? $node : null;
        }

        // this is not a full tca definition. Lets check some fuzzier stuff.
        // it could be a list of columns, as in ExtensionManagementUtility::addTcaColums('table', $node)
        foreach ($node->items as $arrayItem) {
            if (! $arrayItem instanceof ArrayItem) {
                // lets play it safe here. a non-associative array is probably not tca.
                continue;
            }

            if (! $arrayItem->key instanceof String_) {
                // the key of a column list in tca is the column name, which needs to be a string.
                continue;
            }

            // we found a single column configuration which is an array
            // (not a call to stuff like ExtensionManagementUtility::getFileFieldTCAConfig)
            if ($arrayItem->value instanceof Array_ && $this->isSingleTcaColumn($arrayItem)) {
                $this->refactorColumn($arrayItem->key, $arrayItem->value);
            }
        }

        return $this->hasAstBeenChanged ? $node : null;
    }

    /**
     * refactors an TCA array such as [ 'column_1' => [ 'label' => 'column 1', 'config' => ... ], 'column_2' => [
     * 'label' => 'column 2', 'config' => ... ] ]
     *
     * @param Array_ $columnsArray a list of TCA definitions for columns
     */
    protected function refactorColumnList(Array_ $columnsArray): void
    {
        foreach ($columnsArray->items as $columnArrayItem) {
            if (! $columnArrayItem instanceof ArrayItem) {
                continue;
            }

            $columnName = $columnArrayItem->key;
            if (! $columnName instanceof Expr) {
                continue;
            }

            $columnTca = $columnArrayItem->value;

            $this->refactorColumn($columnName, $columnTca);
        }
    }

    /**
     * @return bool whether or not the given Array_ is a full TCA definition for a Table
     */
    protected function isFullTcaDefinition(Array_ $possibleTcaArray): bool
    {
        $columnsArray = $this->extractSubArrayByKey($possibleTcaArray, 'columns');
        $ctrl = $this->extractArrayItemByKey($possibleTcaArray, 'ctrl');

        return $columnsArray instanceof Array_ && $ctrl instanceof ArrayItem;
    }

    /**
     * @return bool whether the given array item is the TCA definition of a single column
     */
    protected function isSingleTcaColumn(ArrayItem $arrayItem): bool
    {
        $labelNode = $this->extractArrayItemByKey($arrayItem->value, self::LABEL);
        if (! $labelNode instanceof ArrayItem) {
            return false;
        }

        $configNode = $this->extractArrayItemByKey($arrayItem->value, self::CONFIG);
        if (! $configNode instanceof ArrayItem) {
            return false;
        }

        $typeNode = $this->extractArrayItemByKey($configNode->value, self::TYPE);
        return $typeNode instanceof ArrayItem;
    }

    /**
     * Refactors a single TCA column definition like 'column_name' => [ 'label' => 'column label', 'config' => [], ]
     *
     * remark: checking if the passed nodes really are a TCA snippet must be checked by the caller.
     *
     * @param Expr $columnName the key in above example (typically String_('column_name'))
     * @param Expr $columnTca the value in above example (typically an associative Array with stuff like 'label', 'config', 'exclude', ...)
     */
    protected function refactorColumn(Expr $columnName, Expr $columnTca): void
    {
        // override this as needed in child-classes
    }

    /**
     * refactors an TCA types array such as [ '0' => [ 'showitem' => 'field_a,field_b' ], '1' => [ 'showitem' =>
     * 'field_a'] ]
     */
    protected function refactorTypes(Array_ $typesArray): void
    {
        foreach ($typesArray->items as $typeItem) {
            if (! $typeItem instanceof ArrayItem) {
                continue;
            }

            $typeKey = $typeItem->key;
            if (! $typeKey instanceof Expr) {
                continue;
            }

            $typeConfig = $typeItem->value;
            $this->refactorType($typeKey, $typeConfig);
        }
    }

    /**
     * refactors a single TCA type item with key `typeKey` such as [ 'showitem' => 'field_a,field_b' ], '1' => [
     * 'showitem' => 'field_a']
     */
    protected function refactorType(Expr $typeKey, Expr $typeConfig): void
    {
        // override this as needed in child-classes
    }

    /**
     * refactors an TCA ctrl section such as ['label' => 'foo', 'tstamp' => 'tstamp', 'crdate' => 'crdate']
     */
    protected function refactorCtrl(Array_ $ctrlArray): void
    {
        // override this as needed in child-classes
    }

    /**
     * may be overridden by child classes to be notified of the start of a node
     */
    protected function resetInnerState(): void
    {
    }

    protected function refactorInterface(Array_ $interfaceArray, Node $node): void
    {
    }

    protected function isFullTca(Return_ $return): bool
    {
        $ctrlArrayItem = $this->extractCtrl($return);
        $columnsArrayItem = $this->extractColumns($return);

        return $ctrlArrayItem instanceof ArrayItem && $columnsArrayItem instanceof ArrayItem;
    }

    /**
     * @param string|int $key
     */
    protected function extractArrayItemByKey(?Node $node, $key): ?ArrayItem
    {
        if (! $node instanceof Node) {
            return null;
        }

        if (! $node instanceof Array_) {
            return null;
        }

        foreach ($node->items as $item) {
            if (! $item instanceof ArrayItem) {
                continue;
            }

            if (! $item->key instanceof Expr) {
                continue;
            }

            $itemKey = $this->getValue($item->key);
            if ($key === $itemKey) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @param string|int $key
     */
    protected function extractSubArrayByKey(?Node $node, $key): ?Array_
    {
        if (! $node instanceof Node) {
            return null;
        }

        $arrayItem = $this->extractArrayItemByKey($node, $key);
        if (! $arrayItem instanceof ArrayItem) {
            return null;
        }

        $columnItems = $arrayItem->value;
        if (! $columnItems instanceof Array_) {
            return null;
        }

        return $columnItems;
    }

    /**
     * @param string|int $key
     */
    protected function extractArrayValueByKey(?Node $node, $key): ?Expr
    {
        return (($extractArrayItemByKey = $this->extractArrayItemByKey(
            $node,
            $key
        )) instanceof ArrayItem) ? $extractArrayItemByKey->value : null;
    }

    /**
     * @param string|int $configKey
     */
    protected function hasKey(Array_ $configValuesArray, $configKey): bool
    {
        foreach ($configValuesArray->items as $configItemValue) {
            if (! $configItemValue instanceof ArrayItem) {
                continue;
            }

            if (! $configItemValue->key instanceof Expr) {
                continue;
            }

            if ($this->isValue($configItemValue->key, $configKey)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $expectedValue
     */
    protected function hasKeyValuePair(Array_ $configValueArray, string $configKey, $expectedValue): bool
    {
        foreach ($configValueArray->items as $configItemValue) {
            if (! $configItemValue instanceof ArrayItem) {
                continue;
            }

            if (! $configItemValue->key instanceof Expr) {
                continue;
            }

            if ($this->isValue($configItemValue->key, $configKey)
                && $this->isValue($configItemValue->value, $expectedValue)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Removes an array key directly from the first level of an array.
     *
     * ```
     * $this->removeArrayItemFromArrayByKey($configArray, 'myKeyToBeRemoved');
     * ```
     *
     * If the key to be removed is in a sub array of the current one
     * use `extractSubArrayByKey` to extract the sub array first:
     *
     * ```
     * $appearanceArray = $this->extractSubArrayByKey($configArray, 'appearance');
     * if (! $appearanceArray instanceof Array_) {
     *     return;
     * }
     * $this->removeArrayItemFromArrayByKey($appearanceArray, 'showRemovedLocalizationRecords');
     * ```
     *
     * Attention: Strict comparison is used for the key. key with int 42 will
     * not remove string "42"!
     *
     * @param string|int $key
     */
    protected function removeArrayItemFromArrayByKey(Array_ $array, $key): void
    {
        $arrayItemToRemove = $this->extractArrayItemByKey($array, $key);
        if (! $arrayItemToRemove instanceof ArrayItem) {
            return;
        }

        foreach ($array->items as $arrayItemKey => $arrayItem) {
            if ($arrayItem === $arrayItemToRemove) {
                unset($array->items[$arrayItemKey]);
                $this->hasAstBeenChanged = true;
            }
        }
    }

    protected function isConfigType(Array_ $columnItemConfigurationArray, string $type): bool
    {
        return $this->hasKeyValuePair($columnItemConfigurationArray, 'type', $type);
    }

    protected function configIsOfRenderType(Array_ $configValueArray, string $expectedRenderType): bool
    {
        return $this->hasKeyValuePair($configValueArray, 'renderType', $expectedRenderType);
    }

    protected function changeTcaType(Array_ $configArray, string $type): void
    {
        $toChangeArrayItem = $this->extractArrayItemByKey($configArray, 'type');
        if ($toChangeArrayItem instanceof ArrayItem) {
            $toChangeArrayItem->value = new String_($type);
        }
    }

    protected function hasRenderType(Array_ $columnItemConfigurationArray): bool
    {
        $renderTypeItem = $this->extractArrayItemByKey($columnItemConfigurationArray, 'renderType');
        return $renderTypeItem instanceof ArrayItem;
    }

    protected function hasInternalType(Array_ $columnItemConfigurationArray): bool
    {
        $internalType = $this->extractArrayItemByKey($columnItemConfigurationArray, 'internal_type');
        return $internalType instanceof ArrayItem;
    }

    protected function configIsOfInternalType(Array_ $configValueArray, string $expectedType): bool
    {
        return $this->hasKeyValuePair($configValueArray, 'internal_type', $expectedType);
    }

    protected function extractColumns(Return_ $return): ?ArrayItem
    {
        return $this->extractArrayItemByKey($return->expr, 'columns');
    }

    protected function extractCtrl(Return_ $return): ?ArrayItem
    {
        return $this->extractArrayItemByKey($return->expr, 'ctrl');
    }

    /**
     * @param mixed $value
     */
    private function isValue(Expr $expr, $value): bool
    {
        return $this->valueResolver->isValue($expr, $value);
    }

    /**
     * @return mixed|null
     */
    private function getValue(Expr $expr)
    {
        return $this->valueResolver->getValue($expr);
    }
}
