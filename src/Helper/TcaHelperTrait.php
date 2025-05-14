<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;

trait TcaHelperTrait
{
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

    /**
     * Removes an array item directly from the first level of an array by index
     */
    protected function removeArrayItemFromArrayByIndex(Array_ $array, int $index): void
    {
        if (isset($array->items[$index])) {
            unset($array->items[$index]);
            $this->hasAstBeenChanged = true;
        }
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
