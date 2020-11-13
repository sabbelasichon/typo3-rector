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

    public function strlen($charset, $string)
    {
        return mb_strlen($string, $charset);
    }

    public function convCapitalize($charset, $string)
    {
        return mb_convert_case($string, MB_CASE_TITLE, $charset);
    }

    public function substr($charset, $string, $start, $len = null)
    {
        return mb_substr($string, $start, $len, $charset);
    }

    public function conv_case($charset, $string, $case)
    {
        return $case === 'toLower'
            ? mb_strtolower($string, $charset)
            : mb_strtoupper($string, $charset);
    }

    public function utf8_strpos($haystack, $needle, $offset = 0)
    {
        return mb_strpos($haystack, $needle, $offset, 'utf-8');
    }

    public function utf8_strrpos($haystack, $needle)
    {
        return mb_strrpos($haystack, $needle, 'utf-8');
    }
}
