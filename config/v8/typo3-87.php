<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use function Rector\SymfonyPhpConfig\inline_value_objects;
use Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector;
use Ssch\TYPO3Rector\Rector\v8\v7\BackendUtilityGetRecordRawRector;
use Ssch\TYPO3Rector\Rector\v8\v7\BackendUtilityGetRecordsByFieldToQueryBuilderRector;
use Ssch\TYPO3Rector\Rector\v8\v7\ChangeAttemptsParameterConsoleOutputRector;
use Ssch\TYPO3Rector\Rector\v8\v7\DataHandlerRmCommaRector;
use Ssch\TYPO3Rector\Rector\v8\v7\DataHandlerVariousMethodsAndMethodArgumentsRector;
use Ssch\TYPO3Rector\Rector\v8\v7\RefactorArrayBrowserWrapValueRector;
use Ssch\TYPO3Rector\Rector\v8\v7\RefactorGraphicalFunctionsTempPathAndCreateTemSubDirRector;
use Ssch\TYPO3Rector\Rector\v8\v7\RefactorPrintContentMethodsRector;
use Ssch\TYPO3Rector\Rector\v8\v7\RefactorRemovedMarkerMethodsFromContentObjectRendererRector;
use Ssch\TYPO3Rector\Rector\v8\v7\RemoveConfigMaxFromInputDateTimeFieldsRector;
use Ssch\TYPO3Rector\Rector\v8\v7\RemoveLocalizationModeKeepIfNeededRector;
use Ssch\TYPO3Rector\Rector\v8\v7\TemplateServiceSplitConfArrayRector;
use Ssch\TYPO3Rector\Rector\v8\v7\UseCachingFrameworkInsteadGetAndStoreHashRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\TypoScript\TypoScriptService as CoreTypoScriptService;
use TYPO3\CMS\Core\Utility\CsvUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\TypoScriptService;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');
    $services = $containerConfigurator->services();
    $services->set(BackendUtilityGetRecordRawRector::class);
    $services->set(DataHandlerRmCommaRector::class);
    $services->set(TemplateServiceSplitConfArrayRector::class);
    $services->set(RefactorRemovedMarkerMethodsFromContentObjectRendererRector::class);
    $services->set(ChangeAttemptsParameterConsoleOutputRector::class);
    $services->set(RenameClassRector::class)->call('configure', [[
        RenameClassRector::OLD_TO_NEW_CLASSES => [
            TypoScriptService::class => CoreTypoScriptService::class,
        ],
    ]]);
    $services->set(RenameClassMapAliasRector::class)->call('configure', [[
        RenameClassMapAliasRector::CLASS_ALIAS_MAPS => [
            __DIR__ . '/../../Migrations/TYPO3/8.7/typo3/sysext/extbase/Migrations/Code/ClassAliasMap.php',
            __DIR__ . '/../../Migrations/TYPO3/8.7/typo3/sysext/fluid/Migrations/Code/ClassAliasMap.php',
            __DIR__ . '/../../Migrations/TYPO3/8.7/typo3/sysext/version/Migrations/Code/ClassAliasMap.php',
        ],
    ]]);
    $services->set(RenameStaticMethodRector::class)->call('configure', [[
        RenameStaticMethodRector::OLD_TO_NEW_METHODS_BY_CLASSES => inline_value_objects([
            new RenameStaticMethod(GeneralUtility::class, 'csvValues', CsvUtility::class, 'csvValues'),
        ]),
    ]]);
    $services->set(BackendUtilityGetRecordsByFieldToQueryBuilderRector::class);
    $services->set(RefactorPrintContentMethodsRector::class);
    $services->set(RefactorArrayBrowserWrapValueRector::class);
    $services->set(DataHandlerVariousMethodsAndMethodArgumentsRector::class);
    $services->set(RefactorGraphicalFunctionsTempPathAndCreateTemSubDirRector::class);
    $services->set(UseCachingFrameworkInsteadGetAndStoreHashRector::class);
    $services->set(RemoveConfigMaxFromInputDateTimeFieldsRector::class);
    $services->set(RemoveLocalizationModeKeepIfNeededRector::class);
};
