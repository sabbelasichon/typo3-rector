<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\v9\v4\BackendUtilityShortcutExistsRector;
use Ssch\TYPO3Rector\Rector\v9\v4\CallEnableFieldsFromPageRepositoryRector;
use Ssch\TYPO3Rector\Rector\v9\v4\ConstantToEnvironmentCallRector;
use Ssch\TYPO3Rector\Rector\v9\v4\DocumentTemplateAddStyleSheetRector;
use Ssch\TYPO3Rector\Rector\v9\v4\RefactorDeprecatedConcatenateMethodsPageRendererRector;
use Ssch\TYPO3Rector\Rector\v9\v4\RefactorExplodeUrl2ArrayFromGeneralUtilityRector;
use Ssch\TYPO3Rector\Rector\v9\v4\RemoveInitMethodGraphicalFunctionsRector;
use Ssch\TYPO3Rector\Rector\v9\v4\RemoveInitMethodTemplateServiceRector;
use Ssch\TYPO3Rector\Rector\v9\v4\RemoveInitTemplateMethodCallRector;
use Ssch\TYPO3Rector\Rector\v9\v4\SystemEnvironmentBuilderConstantsRector;
use Ssch\TYPO3Rector\Rector\v9\v4\UseAddJsFileInsteadOfLoadJavascriptLibRector;
use Ssch\TYPO3Rector\Rector\v9\v4\UseContextApiForVersioningWorkspaceIdRector;
use Ssch\TYPO3Rector\Rector\v9\v4\UseContextApiRector;
use Ssch\TYPO3Rector\Rector\v9\v4\UseGetMenuInsteadOfGetFirstWebPageRector;
use Ssch\TYPO3Rector\Rector\v9\v4\UseLanguageAspectForTsfeLanguagePropertiesRector;
use Ssch\TYPO3Rector\Rector\v9\v4\UseRootlineUtilityInsteadOfGetRootlineMethodRector;
use Ssch\TYPO3Rector\Rector\v9\v4\UseSignalTablesDefinitionIsBeingBuiltSqlExpectedSchemaServiceRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');

    $services = $containerConfigurator->services();

    $services->set(RefactorDeprecatedConcatenateMethodsPageRendererRector::class);

    $services->set(CallEnableFieldsFromPageRepositoryRector::class);

    $services->set(ConstantToEnvironmentCallRector::class);

    $services->set(RemoveInitTemplateMethodCallRector::class);

    $services->set(UseContextApiRector::class);

    $services->set(RefactorExplodeUrl2ArrayFromGeneralUtilityRector::class);

    $services->set(SystemEnvironmentBuilderConstantsRector::class);

    $services->set(UseContextApiForVersioningWorkspaceIdRector::class);

    $services->set(DocumentTemplateAddStyleSheetRector::class);

    $services->set(UseLanguageAspectForTsfeLanguagePropertiesRector::class);

    $services->set(BackendUtilityShortcutExistsRector::class);

    $services->set(UseSignalTablesDefinitionIsBeingBuiltSqlExpectedSchemaServiceRector::class);

    $services->set(UseGetMenuInsteadOfGetFirstWebPageRector::class);

    $services->set(RemoveInitMethodGraphicalFunctionsRector::class);

    $services->set(RemoveInitMethodTemplateServiceRector::class);

    $services->set(UseAddJsFileInsteadOfLoadJavascriptLibRector::class);

    $services->set(UseRootlineUtilityInsteadOfGetRootlineMethodRector::class);
};
