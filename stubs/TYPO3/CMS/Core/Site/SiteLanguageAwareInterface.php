<?php

namespace TYPO3\CMS\Core\Site;

use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

if (interface_exists('TYPO3\CMS\Core\Site\SiteLanguageAwareInterface')) {
    return;
}

interface SiteLanguageAwareInterface
{
    public function setSiteLanguage(SiteLanguage $siteLanguage);

    public function getSiteLanguage(): SiteLanguage;
}
