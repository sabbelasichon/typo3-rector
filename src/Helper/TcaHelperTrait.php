<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

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
        return $this->extractByTypeOnFirstLevel($node);
    }

    private function extractCtrl(Return_ $node): ?ArrayItem
    {
        return $this->extractByTypeOnFirstLevel($node, 'ctrl');
    }

    private function extractInterface(Return_ $node): ?ArrayItem
    {
        return $this->extractByTypeOnFirstLevel($node, 'interface');
    }

    private function extractByTypeOnFirstLevel(Return_ $node, string $type = 'columns'): ?ArrayItem
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
}
