<?php
declare(strict_types=1);


namespace TYPO3\CMS\Backend\Template;

if (class_exists('TYPO3\CMS\Backend\Template\ModuleTemplate')) {
    return;
}

class ModuleTemplate
{
    public function loadJavascriptLib($lib): void
    {
    }

    public function renderContent(): void
    {
    }
}
