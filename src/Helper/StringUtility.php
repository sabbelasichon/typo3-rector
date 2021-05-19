<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use Stringy\Stringy;

final class StringUtility
{
    public static function prepareExtensionName(string $extensionName, int $delimiterPosition): string
    {
        $extensionName = substr($extensionName, $delimiterPosition + 1);

        $stringy = new Stringy($extensionName);
        $underScoredExtensionName = (string) $stringy->underscored()
            ->toLowerCase()
            ->humanize();

        $underScoredExtensionName = ucwords($underScoredExtensionName);

        return str_replace(' ', '', $underScoredExtensionName);
    }
}
