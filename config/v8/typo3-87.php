<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\DefaultSwitchFluidRector;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\PostRector\LibFluidContentToContentElementTypoScriptPostRector;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\LibFluidContentToLibContentElementRector;
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
use Ssch\TYPO3Rector\Rector\v8\v7\TemplateServiceSplitConfArrayRector;
use Ssch\TYPO3Rector\Rector\v8\v7\UseCachingFrameworkInsteadGetAndStoreHashRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set(BackendUtilityGetRecordRawRector::class);
    $services->set(DataHandlerRmCommaRector::class);
    $services->set(TemplateServiceSplitConfArrayRector::class);
    $services->set(RefactorRemovedMarkerMethodsFromContentObjectRendererRector::class);
    $services->set(ChangeAttemptsParameterConsoleOutputRector::class);
    $services->set(RenameClassRector::class)
        ->configure([
            'TYPO3\CMS\Extbase\Service\TypoScriptService' => 'TYPO3\CMS\Core\TypoScript\TypoScriptService',
        ]);
    $services->set(RenameClassMapAliasRector::class)
        ->configure([
            __DIR__ . '/../../Migrations/TYPO3/8.7/typo3/sysext/extbase/Migrations/Code/ClassAliasMap.php',
            __DIR__ . '/../../Migrations/TYPO3/8.7/typo3/sysext/fluid/Migrations/Code/ClassAliasMap.php',
            __DIR__ . '/../../Migrations/TYPO3/8.7/typo3/sysext/version/Migrations/Code/ClassAliasMap.php',
        ]);
    $services->set(RenameStaticMethodRector::class)
        ->configure([
            new RenameStaticMethod(
                'TYPO3\CMS\Core\Utility\GeneralUtility',
                'csvValues',
                'TYPO3\CMS\Core\Utility\CsvUtility',
                'csvValues'
            ),
        ]);
    $services->set(BackendUtilityGetRecordsByFieldToQueryBuilderRector::class);
    $services->set(RefactorPrintContentMethodsRector::class);
    $services->set(RefactorArrayBrowserWrapValueRector::class);
    $services->set(DataHandlerVariousMethodsAndMethodArgumentsRector::class);
    $services->set(RefactorGraphicalFunctionsTempPathAndCreateTemSubDirRector::class);
    $services->set(UseCachingFrameworkInsteadGetAndStoreHashRector::class);
    $services->set(DefaultSwitchFluidRector::class);
    $services->set(LibFluidContentToLibContentElementRector::class);
    $services->set(LibFluidContentToContentElementTypoScriptPostRector::class);
};
