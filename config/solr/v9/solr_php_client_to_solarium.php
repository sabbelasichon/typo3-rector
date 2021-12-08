<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Name\RenameClassRector;
use Ssch\TYPO3Rector\Rector\Extensions\solr\v9\ApacheSolrDocumentToSolariumDocumentRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../config.php');

    $services = $containerConfigurator->services();
    $services->set(ApacheSolrDocumentToSolariumDocumentRector::class);
    $services->set(RenameClassRector::class)
        ->configure([
            'Apache_Solr_Document' => 'ApacheSolrForTypo3\Solr\System\Solr\Document\Document',
            'Apache_Solr_Response' => 'ApacheSolrForTypo3\Solr\System\Solr\ResponseAdapter',
        ]);
};
