<?php

namespace TYPO3\CMS\Core\Database\Query\Expression;

if (class_exists('TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder')) {
    return;
}

class ExpressionBuilder
{
    const EQ = 1;

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

    public function comparison($trim, int $EQ, string $createNamedParameter)
    {
    }

    public function trim(string $string, int $int): string
    {
        return '';
    }

    /**
     * @param mixed $value
     */
    public function lte(string $fieldName, $value): string
    {
    }
}
