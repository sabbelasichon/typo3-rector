<?php
declare(strict_types=1);


namespace TYPO3\CMS\Core\Utility;

if (class_exists('TYPO3\CMS\Core\Utility\CsvUtility')) {
    return;
}

class CsvUtility
{
    public static function csvValues(array $row, $delim = ',', $quote = '"'): void
    {

    }
}
