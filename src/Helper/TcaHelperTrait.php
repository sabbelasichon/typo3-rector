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
        foreach ($columnItemConfiguration->items as $configValue) {
            if (null === $configValue) {
                continue;
            }

            if (! $configValue instanceof ArrayItem) {
                continue;
            }

            if (null === $configValue->key) {
                continue;
            }

            if (! $this->isValue($configValue->key, 'type')) {
                continue;
            }

            if ($this->isValue($configValue->value, $type)) {
                return true;
            }
        }

        return false;
    }

    private function hasRenderType(Array_ $columnItemConfiguration): bool
    {
        foreach ($columnItemConfiguration->items as $configValue) {
            if (null === $configValue) {
                continue;
            }

            if (! $configValue instanceof ArrayItem) {
                continue;
            }

            if (null === $configValue->key) {
                continue;
            }

            if (! $this->isValue($configValue->key, 'renderType')) {
                continue;
            }

            return true;
        }

        return false;
    }

    private function extractColumns(Return_ $node): ?ArrayItem
    {
        return $this->extractByTypeOnFirstLevel($node, 'columns');
    }

    private function extractTypes(Return_ $node): ?ArrayItem
    {
        return $this->extractByTypeOnFirstLevel($node, 'types');
    }

    private function extractCtrl(Return_ $node): ?ArrayItem
    {
        return $this->extractByTypeOnFirstLevel($node, 'ctrl');
    }

    private function extractInterface(Return_ $node): ?ArrayItem
    {
        return $this->extractByTypeOnFirstLevel($node, 'interface');
    }

    private function extractByTypeOnFirstLevel(Return_ $node, string $type): ?ArrayItem
    {
        if (! $node->expr instanceof Array_) {
            return null;
        }

        $items = $node->expr->items;

        foreach ($items as $item) {
            if (! $item instanceof ArrayItem) {
                continue;
            }

            if (null === $item->key) {
                continue;
            }

            $itemKey = (string) $this->getValue($item->key);
            if ($type === $itemKey) {
                return $item;
            }
        }

        return null;
    }

    private function configIsOfType(Array_ $configValue, string $expectedType): bool
    {
        return $this->configKeyIsOfValue($configValue, 'type', $expectedType);
    }

    private function configIsOfInternalType(Array_ $configValue, string $expectedType): bool
    {
        return $this->configKeyIsOfValue($configValue, 'internal_type', $expectedType);
    }

    private function configIsOfRenderType(Array_ $configValue, string $expectedRenderType): bool
    {
        return $this->configKeyIsOfValue($configValue, 'renderType', $expectedRenderType);
    }

    private function configKeyIsOfValue(Array_ $configValue, string $configKey, string $expectedValue): bool
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
    private function extractColumnConfig(Array_ $items): Generator
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

                if (! $this->isValue($configValue->key, 'config')) {
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
