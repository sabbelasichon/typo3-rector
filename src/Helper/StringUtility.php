<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use Symfony\Component\String\UnicodeString;

final class StringUtility
{
    public static function prepareExtensionName(string $extensionName, int $delimiterPosition): string
    {
        $extensionName = substr($extensionName, $delimiterPosition + 1);

        $stringy = new UnicodeString($extensionName);
        $underscores = $stringy->snake();
        $lower = $underscores->lower();

        $underScoredExtensionName = str_replace('_', ' ', $lower->toString());

        $stringy = new UnicodeString($underScoredExtensionName);
        $trimmed = $stringy->trim();
        $uppercase = $trimmed->title();

        $underScoredExtensionName = ucwords($uppercase->toString());

        return str_replace(' ', '', $underScoredExtensionName);
    }

    /**
     * Check if an item exists in a comma-separated list of items.
     *
     * @param string $list Comma-separated list of items (string)
     * @param string $item Item to check for
     * @return bool TRUE if $item is in $list
     */
    public static function inList(string $list, string $item): bool
    {
        return str_contains(',' . $list . ',', ',' . $item . ',');
    }
}
