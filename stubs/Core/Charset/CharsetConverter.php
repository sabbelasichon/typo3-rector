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

    public function strlen($charset, $string): void
    {
    }

    public function convCapitalize($charset, $string): void
    {
    }

    public function substr($charset, $string, $start, $len = null): void
    {
    }

    public function conv_case($charset, $string, $case): void
    {
    }

    public function utf8_strpos($haystack, $needle, $offset = 0): void
    {
    }

    public function utf8_strrpos($haystack, $needle): void
    {
    }

    public function conv($inputString, $fromCharset, $toCharset, $useEntityForNoChar = false): void
    {
    }
}
