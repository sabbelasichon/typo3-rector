<?php

namespace TYPO3\CMS\Backend\Template;

use Psr\Http\Message\ServerRequestInterface;

if (class_exists('TYPO3\CMS\Backend\Template\ModuleTemplateFactory')) {
    return;
}

class ModuleTemplateFactory
{
    public function create(ServerRequestInterface $request): ModuleTemplate
    {
        return new ModuleTemplate();
    }
}
