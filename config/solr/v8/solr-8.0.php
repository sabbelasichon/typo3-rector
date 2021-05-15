<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\Extensions\solr\v8\SolrConnectionAddDocumentsToWriteServiceAddDocumentsRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../config.php');

    $services = $containerConfigurator->services();
    $services->set(SolrConnectionAddDocumentsToWriteServiceAddDocumentsRector::class);
};
