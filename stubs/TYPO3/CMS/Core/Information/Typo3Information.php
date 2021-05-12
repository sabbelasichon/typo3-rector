<?php


namespace TYPO3\CMS\Core\Information;

if (class_exists(Typo3Information::class)) {
    return;
}

class Typo3Information
{
    public function getCopyrightNotice(): string
    {
        return 'notice';
    }
}
