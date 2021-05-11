<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use Generator;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\PhpParser\Node\Value\ValueResolver;

trait TcaHelperTrait
{
    /**
     * @var ValueResolver
     */
    protected $valueResolver;

    private function isTca(Return_ $node): bool
    {
        $ctrl = $this->extractCtrl($node);
        $columns = $this->extractColumns($node);

        return null !== $ctrl && null !== $columns;
    }

    private function isInlineType(Array_ $columnItemConfiguration): bool
    {
        return $this->isConfigType($columnItemConfiguration, 'inline');
    }

    private function isConfigType(Array_ $columnItemConfiguration, string $type): bool
    {
        return $this->hasKeyValuePair($columnItemConfiguration, 'type', $type);
    }

    private function hasRenderType(Array_ $columnItemConfiguration): bool
    {
        $renderTypeItem = $this->extractArrayItemByKey($columnItemConfiguration, 'renderType');
        return null !== $renderTypeItem;
    }

    private function extractColumns(Return_ $node): ?ArrayItem
    {
        return $this->extractArrayItemByKey($node->expr, 'columns');
    }

    private function extractTypes(Return_ $node): ?ArrayItem
    {
        return $this->extractArrayItemByKey($node->expr, 'types');
    }

    private function extractCtrl(Return_ $node): ?ArrayItem
    {
        return $this->extractArrayItemByKey($node->expr, 'ctrl');
    }

    private function extractInterface(Return_ $node): ?ArrayItem
    {
        return $this->extractArrayItemByKey($node->expr, 'interface');
    }

    private function extractArrayItemByKey(?Node $node, string $key): ?ArrayItem
    {
        if (null === $node) {
            return null;
        }

        if (! $node instanceof Array_) {
            return null;
        }

        foreach ($node->items as $item) {
            if (! $item instanceof ArrayItem) {
                continue;
            }

            if (null === $item->key) {
                continue;
            }

            $itemKey = (string) $this->getValue($item->key);
            if ($key === $itemKey) {
                return $item;
            }
        }

        return null;
    }

    private function extractSubArrayByKey(?Node $node, string $key): ?Array_
    {
        if (null === $node) {
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
     * @return Generator<ArrayItem>
     */
    private function extractSubArraysWithArrayItemMatching(Array_ $array, string $key, string $value): Generator
    {
        foreach ($array->items as $arrayItem) {
            if (! $arrayItem instanceof ArrayItem) {
                continue;
            }
            if (! $arrayItem->value instanceof Array_) {
                continue;
            }
            if (! $this->hasKeyValuePair($arrayItem->value, $key, $value)) {
                continue;
            }
            yield $arrayItem;
        }
        return null;
    }

    private function configIsOfInternalType(Array_ $configValue, string $expectedType): bool
    {
        return $this->hasKeyValuePair($configValue, 'internal_type', $expectedType);
    }

    private function configIsOfRenderType(Array_ $configValue, string $expectedRenderType): bool
    {
        return $this->hasKeyValuePair($configValue, 'renderType', $expectedRenderType);
    }

    private function hasKeyValuePair(Array_ $configValue, string $configKey, string $expectedValue): bool
    {
        foreach ($configValue->items as $configItemValue) {
            if (! $configItemValue instanceof ArrayItem) {
                continue;
            }

            if (null === $configItemValue->key) {
                continue;
            }

            if ($this->isValue($configItemValue->key, $configKey) && $this->isValue(
                $configItemValue->value,
                $expectedValue
            )) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Generator<string, Node>
     */
    private function extractColumnConfig(Array_ $items, string $keyName = 'config'): Generator
    {
        foreach ($items->items as $columnConfig) {
            if (! $columnConfig instanceof ArrayItem) {
                continue;
            }

            if (null === $columnConfig->key) {
                continue;
            }

            $columnName = $this->getValue($columnConfig->key);

            if (null === $columnName) {
                continue;
            }

            if (! $columnConfig->value instanceof Array_) {
                continue;
            }

            // search the config sub-array for this field
            foreach ($columnConfig->value->items as $configValue) {
                if (null === $configValue || null === $configValue->key) {
                    continue;
                }

                if (! $this->isValue($configValue->key, $keyName)) {
                    continue;
                }

                yield $columnName => $configValue->value;
            }
        }
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
