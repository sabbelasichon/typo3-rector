<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use Generator;
use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Stmt\Return_;

trait TcaHelperTrait
{
    private function isTca(Return_ $node): bool
    {
        if (! $node->expr instanceof Array_) {
            return false;
        }

        $items = $node->expr->items;

        $ctrl = null;
        $columns = null;

        foreach ($items as $item) {
            if (! $item instanceof ArrayItem) {
                continue;
            }

            if (null === $item->key) {
                continue;
            }

            $itemKey = (string) $this->getValue($item->key);
            if ('ctrl' === $itemKey) {
                $ctrl = $item;
            } elseif ('columns' === $itemKey) {
                $columns = $item;
            }
        }
        return null !== $ctrl && null !== $columns;
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

    /**
     * @return Generator<string, Node>
     */
    private function extractColumnConfig(Array_ $items)
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
}
