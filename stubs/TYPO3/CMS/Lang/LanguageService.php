<?php
declare(strict_types=1);

namespace TYPO3\CMS\Lang;

if(class_exists('TYPO3\CMS\Lang\LanguageService'))
{
    return;
}

class LanguageService
{
    public function sL($input, $hsc = false): void
    {
    }

    public function getLL($index, $hsc = false): void
    {
    }

    public function getLLL($index, $localLanguage, $hsc = false): void
    {
    }
}
