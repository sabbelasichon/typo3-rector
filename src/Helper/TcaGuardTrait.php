<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Stmt\Return_;

trait TcaGuardTrait
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
            $itemKey = (string) $this->getValue($item->key);
            if ('ctrl' === $itemKey) {
                $ctrl = $item;
            } elseif ('columns' === $itemKey) {
                $columns = $item;
            }
        }

        if (null === $ctrl || null === $columns) {
            return false;
        }

        return true;
    }
}
