<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Name\RenameClassRector;
use Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector;
use Ssch\TYPO3Rector\Rector\v8\v7\BackendUtilityGetRecordRawRector;
use Ssch\TYPO3Rector\Rector\v8\v7\ChangeAttemptsParameterConsoleOutputRector;
use Ssch\TYPO3Rector\Rector\v8\v7\DataHandlerRmCommaRector;
use Ssch\TYPO3Rector\Rector\v8\v7\RefactorRemovedMarkerMethodsFromContentObjectRendererRector;
use Ssch\TYPO3Rector\Rector\v8\v7\TemplateServiceSplitConfArrayRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\TypoScript\TypoScriptService as CoreTypoScriptService;
use TYPO3\CMS\Extbase\Service\TypoScriptService;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');

    $services = $containerConfigurator->services();

    $services->set(BackendUtilityGetRecordRawRector::class);

    $services->set(DataHandlerRmCommaRector::class);

    $services->set(TemplateServiceSplitConfArrayRector::class);

    $services->set(RefactorRemovedMarkerMethodsFromContentObjectRendererRector::class);

    $services->set(ChangeAttemptsParameterConsoleOutputRector::class);

    $services->set(RenameClassRector::class)
        ->call('configure', [[
            RenameClassRector::OLD_TO_NEW_CLASSES => [
                TypoScriptService::class => CoreTypoScriptService::class,
            ],
        ]]);

    $services->set(RenameClassMapAliasRector::class)
        ->call('configure', [
            [
                RenameClassMapAliasRector::CLASS_ALIAS_MAPS => [
                    __DIR__ . '/../Migrations/TYPO3/8.7/typo3/sysext/extbase/Migrations/Code/ClassAliasMap.php',
                    __DIR__ . '/../Migrations/TYPO3/8.7/typo3/sysext/fluid/Migrations/Code/ClassAliasMap.php',
                    __DIR__ . '/../Migrations/TYPO3/8.7/typo3/sysext/version/Migrations/Code/ClassAliasMap.php',
                ],
            ],
        ]);
};
