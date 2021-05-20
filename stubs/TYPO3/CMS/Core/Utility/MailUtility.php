<?php
declare(strict_types=1);


namespace TYPO3\CMS\Core\Utility;

if (class_exists('TYPO3\CMS\Core\Utility\MailUtility')) {
    return;
}

class MailUtility
{
    public static function parseAddresses($rawAddresses): array
    {
        return [];
    }
}
