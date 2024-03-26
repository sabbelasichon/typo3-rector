<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

final class StringUtility
{
    public static function prepareExtensionName(string $extensionName, int $delimiterPosition): string
    {
        $extensionName = substr($extensionName, $delimiterPosition + 1);

        return self::extensionKeyToExtensionName($extensionName);
    }

    /**
     * Copied from TYPO3 Core: \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin
     */
    public static function extensionKeyToExtensionName(string $extensionKey): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $extensionKey)));
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
