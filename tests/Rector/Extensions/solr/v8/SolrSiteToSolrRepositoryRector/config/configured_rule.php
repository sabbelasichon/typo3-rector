<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

use Ssch\TYPO3Rector\Rector\Extensions\solr\v8\SolrSiteToSolrRepositoryRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(SolrSiteToSolrRepositoryRector::class);
    $rectorConfig->importNames();
};
