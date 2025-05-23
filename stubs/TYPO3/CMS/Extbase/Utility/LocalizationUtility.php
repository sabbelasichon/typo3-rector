<?php

namespace TYPO3\CMS\Extbase\Utility;

if (class_exists('TYPO3\CMS\Extbase\Utility\LocalizationUtility')) {
    return;
}

class LocalizationUtility
{
    public static function translate(string $key, ?string $extensionName = null, array $arguments = null, string $languageKey = null, array $alternativeLanguageKeys = null): ?string
    {
        return '';
    }
}
