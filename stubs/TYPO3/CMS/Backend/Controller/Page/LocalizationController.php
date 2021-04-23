<?php

declare(strict_types=1);

namespace TYPO3\CMS\Backend\Controller\Page;

if (class_exists(LocalizationController::class)) {
    return;
}

class LocalizationController
{
    public function getUsedLanguagesInPageAndColumn(): void
    {
    }
}
