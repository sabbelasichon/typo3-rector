<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v9\v4\AdditionalFieldProviderRector;
use Ssch\TYPO3Rector\Rector\v9\v4\BackendUtilityShortcutExistsRector;
use Ssch\TYPO3Rector\Rector\v9\v4\CallEnableFieldsFromPageRepositoryRector;
use Ssch\TYPO3Rector\Rector\v9\v4\ConstantsToEnvironmentApiCallRector;
use Ssch\TYPO3Rector\Rector\v9\v4\DocumentTemplateAddStyleSheetRector;
use Ssch\TYPO3Rector\Rector\v9\v4\GeneralUtilityGetHostNameToGetIndpEnvRector;
use Ssch\TYPO3Rector\Rector\v9\v4\RefactorDeprecatedConcatenateMethodsPageRendererRector;
use Ssch\TYPO3Rector\Rector\v9\v4\RefactorExplodeUrl2ArrayFromGeneralUtilityRector;
use Ssch\TYPO3Rector\Rector\v9\v4\RemoveInitMethodGraphicalFunctionsRector;
use Ssch\TYPO3Rector\Rector\v9\v4\RemoveInitMethodTemplateServiceRector;
use Ssch\TYPO3Rector\Rector\v9\v4\RemoveInitTemplateMethodCallRector;
use Ssch\TYPO3Rector\Rector\v9\v4\RemoveMethodsFromEidUtilityAndTsfeRector;
use Ssch\TYPO3Rector\Rector\v9\v4\SystemEnvironmentBuilderConstantsRector;
use Ssch\TYPO3Rector\Rector\v9\v4\TemplateGetFileNameToFilePathSanitizerRector;
use Ssch\TYPO3Rector\Rector\v9\v4\UseAddJsFileInsteadOfLoadJavascriptLibRector;
use Ssch\TYPO3Rector\Rector\v9\v4\UseClassSchemaInsteadReflectionServiceMethodsRector;
use Ssch\TYPO3Rector\Rector\v9\v4\UseContextApiForVersioningWorkspaceIdRector;
use Ssch\TYPO3Rector\Rector\v9\v4\UseContextApiRector;
use Ssch\TYPO3Rector\Rector\v9\v4\UseGetMenuInsteadOfGetFirstWebPageRector;
use Ssch\TYPO3Rector\Rector\v9\v4\UseLanguageAspectForTsfeLanguagePropertiesRector;
use Ssch\TYPO3Rector\Rector\v9\v4\UseRootlineUtilityInsteadOfGetRootlineMethodRector;
use Ssch\TYPO3Rector\Rector\v9\v4\UseSignalAfterExtensionInstallInsteadOfHasInstalledExtensionsRector;
use Ssch\TYPO3Rector\Rector\v9\v4\UseSignalTablesDefinitionIsBeingBuiltSqlExpectedSchemaServiceRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(RefactorDeprecatedConcatenateMethodsPageRendererRector::class);
    $rectorConfig->rule(CallEnableFieldsFromPageRepositoryRector::class);
    $rectorConfig->rule(ConstantsToEnvironmentApiCallRector::class);
    $rectorConfig->rule(RemoveInitTemplateMethodCallRector::class);
    $rectorConfig->rule(UseContextApiRector::class);
    $rectorConfig->rule(RefactorExplodeUrl2ArrayFromGeneralUtilityRector::class);
    $rectorConfig->rule(SystemEnvironmentBuilderConstantsRector::class);
    $rectorConfig->rule(UseContextApiForVersioningWorkspaceIdRector::class);
    $rectorConfig->rule(DocumentTemplateAddStyleSheetRector::class);
    $rectorConfig->rule(UseLanguageAspectForTsfeLanguagePropertiesRector::class);
    $rectorConfig->rule(BackendUtilityShortcutExistsRector::class);
    $rectorConfig->rule(UseSignalTablesDefinitionIsBeingBuiltSqlExpectedSchemaServiceRector::class);
    $rectorConfig->rule(UseGetMenuInsteadOfGetFirstWebPageRector::class);
    $rectorConfig->rule(RemoveInitMethodGraphicalFunctionsRector::class);
    $rectorConfig->rule(RemoveInitMethodTemplateServiceRector::class);
    $rectorConfig->rule(UseAddJsFileInsteadOfLoadJavascriptLibRector::class);
    $rectorConfig->rule(UseRootlineUtilityInsteadOfGetRootlineMethodRector::class);
    $rectorConfig->rule(TemplateGetFileNameToFilePathSanitizerRector::class);
    $rectorConfig->rule(UseSignalAfterExtensionInstallInsteadOfHasInstalledExtensionsRector::class);
    $rectorConfig->rule(UseClassSchemaInsteadReflectionServiceMethodsRector::class);
    $rectorConfig->rule(RemoveMethodsFromEidUtilityAndTsfeRector::class);
    $rectorConfig->rule(AdditionalFieldProviderRector::class);
    $rectorConfig->rule(GeneralUtilityGetHostNameToGetIndpEnvRector::class);
};
