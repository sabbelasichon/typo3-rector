<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Extensions\solr\v9\ApacheSolrDocumentToSolariumDocumentRector\Fixture;

use Apache_Solr_Document;

class MySolrIndexer
{
    public function __construct()
    {
    }

    public function index()
    {
        $document = new Apache_Solr_Document();
        $document->setMultiValue('key', 'foo', true);
    }

    /**
     * @param Apache_Solr_Document[] $documents
     */
    public function multiple(array $documents)
    {
        foreach ($documents as $document) {
            $document->setMultiValue('key', 'foo', true);
        }
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Extensions\solr\v9\ApacheSolrDocumentToSolariumDocumentRector\Fixture;

use ApacheSolrForTypo3\Solr\System\Solr\Document\Document;
use Apache_Solr_Document;

class MySolrIndexer
{
    public function __construct()
    {
    }

    public function index()
    {
        $document = new Document();
        $document->addField('key', 'foo', true);
    }

    /**
     * @param Document[] $documents
     */
    public function multiple(array $documents)
    {
        foreach ($documents as $document) {
            $document->addField('key', 'foo', true);
        }
    }
}

?>
