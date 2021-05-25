<?php
namespace TYPO3\CMS\Core\Site\Entity;

use TYPO3\CMS\Core\Routing\PageRouter;

if (class_exists('TYPO3\CMS\Core\Site\Entity\Site')) {
    return;
}

class Site
{
    /**
     * @return \TYPO3\CMS\Core\Routing\PageRouter
     */
    public function getRouter()
    {
        return new PageRouter();
    }
}
