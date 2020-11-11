<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use function Rector\SymfonyPhpConfig\inline_value_objects;
use Ssch\TYPO3Rector\Rector\v7\v0\RemoveMethodCallConnectDbRector;
use Ssch\TYPO3Rector\Rector\v7\v0\RemoveMethodCallLoadTcaRector;
use Ssch\TYPO3Rector\Rector\v7\v0\TypeHandlingServiceToTypeHandlingUtilityRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Backend\Template\BigDocumentTemplate;
use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Backend\Template\MediumDocumentTemplate;
use TYPO3\CMS\Backend\Template\SmallDocumentTemplate;
use TYPO3\CMS\Backend\Template\StandardDocumentTemplate;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');

    $services = $containerConfigurator->services();

    $services->set(RemoveMethodCallConnectDbRector::class);
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

    $services->set(RenameStaticMethodRector::class)
        ->call('configure', [[
            RenameStaticMethodRector::OLD_TO_NEW_METHODS_BY_CLASSES => inline_value_objects([
                new RenameStaticMethod(
                   GeneralUtility::class,
                   'int_from_ver',
                   VersionNumberUtility::class,
                   'convertVersionNumberToInteger'
                    ),
            ]),
        ]]);

    $services->set(TypeHandlingServiceToTypeHandlingUtilityRector::class);
};
