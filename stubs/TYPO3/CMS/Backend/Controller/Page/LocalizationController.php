<?php

declare(strict_types=1);

namespace TYPO3\CMS\Backend\Controller\Page;

if (class_exists('TYPO3\CMS\Backend\Controller\Page\LocalizationController')) {
    return;
}

class LocalizationController
{
    public function getUsedLanguagesInPageAndColumn(): void
    {
    }
}
