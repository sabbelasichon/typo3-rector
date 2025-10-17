<?php

namespace TYPO3\CMS\Core\Localization;

if (class_exists('TYPO3\CMS\Core\Localization\LocalizationFactory')) {
    return;
}

class LocalizationFactory
{
    /**
     * @return array
     */
    public function getParsedData($fileRef, $langKey, $charset = null, $errorMode = null, $isLocalizationOverride = null)
    {
        return [];
    }
}
