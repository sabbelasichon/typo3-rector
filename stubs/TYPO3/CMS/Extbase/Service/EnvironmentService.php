<?php

namespace TYPO3\CMS\Extbase\Service;

if (class_exists('TYPO3\CMS\Extbase\Service\EnvironmentService')) {
    return;
}

class EnvironmentService
{
    public function isEnvironmentInCliMode(): bool
    {
        return true;
    }

    public function isEnvironmentInFrontendMode(): bool
    {
        return true;
    }

    public function isEnvironmentInBackendMode(): bool
    {
        return true;
    }
}
