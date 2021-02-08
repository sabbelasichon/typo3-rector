<?php

declare(strict_types=1);

// $this->_fields['content'] -> $this->fields['content'];
// $document->getField('type_stringS') -> $document['type_stringS']
// $document->fooBarBaz -> $document['fooBarBaz']

use ApacheSolrForTypo3\Solr\System\Solr\Document\Document;
use ApacheSolrForTypo3\Solr\System\Solr\ResponseAdapter;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Ssch\TYPO3Rector\Rector\Extensions\solr\ApacheSolrDocumentToSolariumDocumentRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');

    $services = $containerConfigurator->services();
    $services->set(ApacheSolrDocumentToSolariumDocumentRector::class);
    $services->set(RenameClassRector::class)
        ->call('configure', [[
            RenameClassRector::OLD_TO_NEW_CLASSES => [
                Apache_Solr_Document::class => Document::class,
                Apache_Solr_Response::class => ResponseAdapter::class,
            ],
        ]]);
};
