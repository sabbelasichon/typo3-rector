<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Information;

if (class_exists('TYPO3\CMS\Core\Information\Typo3Information')) {
    return;
}

class Typo3Information
{
    public function getCopyrightNotice(): string
    {
        return 'notice';
    }
}
