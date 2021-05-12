<?php


namespace TYPO3\CMS\Extbase\Mvc\Controller;

if (class_exists(CommandController::class)) {
    return;
}

class CommandController
{
    protected function getBackendUserAuthentication(): void
    {
    }
}
