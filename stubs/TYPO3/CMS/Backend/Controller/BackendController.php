<?php
namespace TYPO3\CMS\Backend\Controller;

use TYPO3\CMS\Core\Page\PageRenderer;

if (class_exists('TYPO3\CMS\Backend\Controller\BackendController')) {
    return;
}

class BackendController
{
    /**
     * @return \TYPO3\CMS\Core\Page\PageRenderer
     */
    public function getPageRenderer()
    {
        return new PageRenderer();
    }
}
