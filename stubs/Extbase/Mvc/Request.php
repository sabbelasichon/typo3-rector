<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Mvc;

final class Request
{
    public function getControllerExtensionName(): string
    {
        return 'extensionName';
    }
}
