<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Mvc;

if (class_exists('TYPO3\CMS\Extbase\Mvc\Request')) {
    return;
}

class Request
{
    public function getControllerExtensionName(): string
    {
        return 'extensionName';
    }
}
