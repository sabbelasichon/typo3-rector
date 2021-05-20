<?php
declare(strict_types=1);


namespace TYPO3\CMS\Core\Information;

if (class_exists('TYPO3\CMS\Core\Information\Typo3Version')) {
    return;
}

class Typo3Version
{
    public function getVersion(): string
    {
        return '9.5.21';
    }

    public function getBranch(): string
    {
        return '9.5';
    }
}
