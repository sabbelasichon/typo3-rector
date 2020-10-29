<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Utility;

if (class_exists(TypeHandlingUtility::class)) {
    return;
}

final class TypeHandlingUtility
{
    public static function hex2bin($hexadecimalData): string
    {
        return 'foo';
    }
}
