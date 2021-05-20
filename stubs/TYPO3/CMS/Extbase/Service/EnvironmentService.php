<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Service;

if (class_exists('TYPO3\CMS\Extbase\Service\EnvironmentService')) {
    return;
}

class EnvironmentService
{
    public function isEnvironmentInCliMode(): string
    {
        return 'foo';
    }
}
