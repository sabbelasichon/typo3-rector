<?php
namespace TYPO3\CMS\Backend\Controller;

use TYPO3\CMS\Backend\Template\ModuleTemplate;

if (class_exists('TYPO3\CMS\Backend\Controller\PageLayoutController')) {
    return;
}

class PageLayoutController
{
    /**
     * @return void
     */
    public function printContent()
    {

    }

    /**
     * @return \TYPO3\CMS\Backend\Template\ModuleTemplate
     */
    public function getModuleTemplate()
    {
        return new ModuleTemplate();
    }
}
