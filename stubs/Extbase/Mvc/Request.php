<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Mvc;

if (class_exists(Request::class)) {
    return;
}

final class Request
{
    public function getControllerExtensionName(): string
    {
        return 'extensionName';
    }
}
