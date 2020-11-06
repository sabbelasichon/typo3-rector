<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Charset;


if (class_exists(CharsetConverter::class)) {
    return;
}

final class CharsetConverter
{
    public function getPreferredClientLanguage($languageCodesList): string
    {
        return 'foo';
    }
}
