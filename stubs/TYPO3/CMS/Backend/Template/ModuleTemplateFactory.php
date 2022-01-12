<?php
namespace TYPO3\CMS\Backend\Template;

use TYPO3\CMS\Extbase\Mvc\Request;

if (class_exists('TYPO3\CMS\Backend\Template\ModuleTemplateFactory')) {
    return;
}

class ModuleTemplateFactory
{
    public function create(Request $request): ModuleTemplate
    {
        return new ModuleTemplate();
    }
}
