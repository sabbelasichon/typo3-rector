<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Utility;

if (class_exists(ArrayUtility::class)) {
    return;
}

final class ArrayUtility
{
    public static function inArray(array $in_array, $item): bool
    {
        return true;
    }
}
