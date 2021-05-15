<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Extensions\solr\v8\SolrConnectionAddDocumentsToWriteServiceAddDocumentsRector\Fixture;

use ApacheSolrForTypo3\Solr\System\Solr\SolrConnection;

class MyService
{
    /**
     * @var SolrConnection
     */
    private $solrConnection;

    /**
     * MyService constructor.
     *
     * @param SolrConnection $solrConnection
     */
    public function __construct(SolrConnection $solrConnection)
    {
        $this->solrConnection = $solrConnection;
    }

    public function addDocuments()
    {
        $this->solrConnection->addDocuments([]);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Extensions\solr\v8\SolrConnectionAddDocumentsToWriteServiceAddDocumentsRector\Fixture;

use ApacheSolrForTypo3\Solr\System\Solr\SolrConnection;

class MyService
{
    /**
     * @var SolrConnection
     */
    private $solrConnection;

    /**
     * MyService constructor.
     *
     * @param SolrConnection $solrConnection
     */
    public function __construct(SolrConnection $solrConnection)
    {
        $this->solrConnection = $solrConnection;
    }

    public function addDocuments()
    {
        $this->solrConnection->getWriteService()->addDocuments([]);
    }
}

?>
