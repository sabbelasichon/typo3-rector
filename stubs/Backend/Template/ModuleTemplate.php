<?php
declare(strict_types=1);


namespace TYPO3\CMS\Backend\Template;

if (class_exists(ModuleTemplate::class)) {
    return;
}

final class ModuleTemplate
{
    public function loadJavascriptLib($lib): void
    {
    }

    public function renderContent(): void
    {
    }
}
