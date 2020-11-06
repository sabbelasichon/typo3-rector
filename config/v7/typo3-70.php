<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Name\RenameClassRector;
use Ssch\TYPO3Rector\Rector\v7\v0\RemoveMethodCallLoadTcaRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Backend\Template\BigDocumentTemplate;
use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Backend\Template\MediumDocumentTemplate;
use TYPO3\CMS\Backend\Template\SmallDocumentTemplate;
use TYPO3\CMS\Backend\Template\StandardDocumentTemplate;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');

    $services = $containerConfigurator->services();

    $services->set(RemoveMethodCallLoadTcaRector::class);
    $services->set(RenameClassRector::class)
        ->call('configure', [[
            RenameClassRector::OLD_TO_NEW_CLASSES => [
                MediumDocumentTemplate::class => DocumentTemplate::class,
                SmallDocumentTemplate::class => DocumentTemplate::class,
                StandardDocumentTemplate::class => DocumentTemplate::class,
                BigDocumentTemplate::class => DocumentTemplate::class,
            ],
             ]]);
};
