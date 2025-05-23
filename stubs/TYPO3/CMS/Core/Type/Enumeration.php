<?php

namespace TYPO3\CMS\Core\Type;

if (class_exists('TYPO3\CMS\Core\Type\Enumeration')) {
    return;
}

abstract class Enumeration
{
    /**
     * @param mixed $value
     */
    public function __construct($value = null)
    {
    }

    public static function cast($value): ?self
    {
        return null;
    }

    public function equals($value): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '';
    }

    public static function tryFrom($param): int
    {
        return 1;
    }
}
