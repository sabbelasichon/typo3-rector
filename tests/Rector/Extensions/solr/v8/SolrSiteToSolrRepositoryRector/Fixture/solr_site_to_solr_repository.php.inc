<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Extensions\solr\v8\SolrSiteToSolrRepositoryRector\Fixture;

use ApacheSolrForTypo3\Solr\Site;
use stdClass;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyService
{
    public function addDocuments()
    {
        $class = GeneralUtility::makeInstance(stdClass::class, 1);
        $site1 = GeneralUtility::makeInstance(Site::class, 1);
        $site2 = GeneralUtility::makeInstance('ApacheSolrForTypo3\Solr\Site', 1);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Extensions\solr\v8\SolrSiteToSolrRepositoryRector\Fixture;

use ApacheSolrForTypo3\Solr\Domain\Site\SiteRepository;
use ApacheSolrForTypo3\Solr\Site;
use stdClass;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyService
{
    public function addDocuments()
    {
        $class = GeneralUtility::makeInstance(stdClass::class, 1);
        $site1 = GeneralUtility::makeInstance(SiteRepository::class)->getSiteByPageId(1);
        $site2 = GeneralUtility::makeInstance(SiteRepository::class)->getSiteByPageId(1);
    }
}

?>
