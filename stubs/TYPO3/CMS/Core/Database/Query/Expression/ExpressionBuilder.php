<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Database\Query\Expression;

if (class_exists('TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder')) {
    return;
}

class ExpressionBuilder
{
    public function eq(string $fieldName, $value): string
    {
        return '';
    }

    public function andX(...$expressions): void
    {
    }

    public function orX(...$expressions): void
    {
    }

    public function and(...$expressions): void
    {
    }

    public function or(...$expressions): void
    {
    }
}
