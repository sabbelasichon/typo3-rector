<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Set\Extension\SolrSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();
    $rectorConfig->sets([SolrSetList::SOLR_SOLR_PHP_CLIENT_TO_SOLARIUM]);
};
