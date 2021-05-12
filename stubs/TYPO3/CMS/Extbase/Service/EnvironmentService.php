<?php


namespace TYPO3\CMS\Extbase\Service;

if (class_exists(EnvironmentService::class)) {
    return;
}

class EnvironmentService
{
    public function isEnvironmentInCliMode(): string
    {
        return 'foo';
    }
}
