<?php
namespace TYPO3\CMS\Core\Site;

use TYPO3\CMS\Core\Site\Entity\Site;

if (class_exists('TYPO3\CMS\Core\Site\SiteFinder')) {
    return;
}

class SiteFinder
{
    /**
     * @param int $pageId
     * @param string $mountPointParameter
     * @return \TYPO3\CMS\Core\Site\Entity\Site
     */
    public function getSiteByPageId($pageId, array $rootLine = null, $mountPointParameter = null)
    {
        $pageId = (int) $pageId;
        $mountPointParameter = (string) $mountPointParameter;
        return new Site();
    }
}
