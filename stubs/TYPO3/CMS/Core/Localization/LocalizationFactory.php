<?php
declare(strict_types=1);


namespace TYPO3\CMS\Core\Localization;

if (class_exists('TYPO3\CMS\Core\Localization\LocalizationFactory')) {
    return;
}

class LocalizationFactory
{
    public function getParsedData($fileRef, $langKey, $charset, $errorMode): void
    {
    }
}
