<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ImplementSiteLanguageAwareInterfaceRector\Fixture;

use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Site\SiteLanguageAwareInterface;

class MyClassWithoutInterfaceButOtherMethods implements SiteLanguageAwareInterface
{
    protected SiteLanguage $siteLanguage;

    public function setSiteLanguage(SiteLanguage $siteLanguage)
    {
        $this->siteLanguage = $siteLanguage;
    }
    public function getSiteLanguage(): SiteLanguage
    {
        return $this->siteLanguage;
    }
    protected SiteLanguage $siteLanguage;
}

?>

