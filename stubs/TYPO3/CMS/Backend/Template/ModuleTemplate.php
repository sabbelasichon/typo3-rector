<?php

namespace TYPO3\CMS\Backend\Template;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\Components\Buttons\Action\ShortcutButton;
use TYPO3\CMS\Backend\Template\Components\DocHeaderComponent;

if (class_exists('TYPO3\CMS\Backend\Template\ModuleTemplate')) {
    return;
}

class ModuleTemplate
{
    /**
     * @return void
     */
    public function loadJavascriptLib($lib)
    {
    }

    /**
     * @return void
     */
    public function renderContent()
    {
    }

    /**
     * @return ResponseInterface
     */
    public function renderResponse(string $templateFileName = '')
    {
    }

    public function getDocHeaderComponent(): DocHeaderComponent
    {
    }

    public function addButtonToButtonBar(ShortcutButton $shortcutButton)
    {
    }
}
