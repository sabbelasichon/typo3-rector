<?php


namespace TYPO3\CMS\Extbase\Mvc;

if (class_exists(Request::class)) {
    return;
}

class Request
{
    public function getControllerExtensionName(): string
    {
        return 'extensionName';
    }
}
