<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Mvc\Controller;

if (class_exists('TYPO3\CMS\Extbase\Mvc\Controller\CommandController')) {
    return;
}

class CommandController
{
    protected function getBackendUserAuthentication(): void
    {
    }
}
