<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Tca;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;

trait TcaHelperTrait
{
    protected function isFullTca(Return_ $return): bool
    {
        $ctrlArrayItem = $this->extractCtrl($return);
        $columnsArrayItem = $this->extractColumns($return);

        return $ctrlArrayItem !== null && $columnsArrayItem !== null;
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
        return ($extractArrayItemByKey = $this->extractArrayItemByKey(
            $node,
            $key
        )) ? $extractArrayItemByKey->value : null;
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

    protected function hasKeyValuePair(Array_ $configValueArray, string $configKey, string $expectedValue): bool
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
     * @param string|int $key
     */
    protected function removeArrayItemFromArrayByKey(Array_ $array, $key): bool
    {
        $arrayItemToRemove = $this->extractArrayItemByKey($array, $key);
        if ($arrayItemToRemove === null) {
            return false;
        }

        foreach ($array->items as $arrayItemKey => $arrayItem) {
            if ($arrayItem === $arrayItemToRemove) {
                unset($array->items[$arrayItemKey]);
                return true;
            }
        }

        return false;
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

    private function hasRenderType(Array_ $columnItemConfigurationArray): bool
    {
        $renderTypeItem = $this->extractArrayItemByKey($columnItemConfigurationArray, 'renderType');
        return $renderTypeItem !== null;
    }

    private function hasInternalType(Array_ $columnItemConfigurationArray): bool
    {
        $internalType = $this->extractArrayItemByKey($columnItemConfigurationArray, 'internal_type');
        return $internalType !== null;
    }

    private function extractColumns(Return_ $return): ?ArrayItem
    {
        return $this->extractArrayItemByKey($return->expr, 'columns');
    }

    private function extractTypes(Return_ $return): ?ArrayItem
    {
        return $this->extractArrayItemByKey($return->expr, 'types');
    }

    private function extractCtrl(Return_ $return): ?ArrayItem
    {
        return $this->extractArrayItemByKey($return->expr, 'ctrl');
    }

    private function extractInterface(Return_ $return): ?ArrayItem
    {
        return $this->extractArrayItemByKey($return->expr, 'interface');
    }

    private function configIsOfInternalType(Array_ $configValueArray, string $expectedType): bool
    {
        return $this->hasKeyValuePair($configValueArray, 'internal_type', $expectedType);
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
    private function getValue(Expr $expr, bool $resolvedClassReference = false)
    {
        return $this->valueResolver->getValue($expr, $resolvedClassReference);
    }
}