<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Extensions\solr\v11\Fixture;

use ApacheSolrForTypo3\Solr\System\Url\UrlHelper;

class MySolrIndexer
{
    public function __construct()
    {
    }

    public function index()
    {
        $urlHelper = new UrlHelper();
        $urlHelper->removeQueryParameter('test');

        $url = $urlHelper->getUrl();
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Extensions\solr\v11\Fixture;

use ApacheSolrForTypo3\Solr\System\Url\UrlHelper;

class MySolrIndexer
{
    public function __construct()
    {
    }

    public function index()
    {
        $urlHelper = new UrlHelper();
        $urlHelper->withoutQueryParameter('test');

        $url = $urlHelper->__toString();
    }
}

?>
