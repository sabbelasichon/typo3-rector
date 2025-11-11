<?php

declare(strict_types=1);

use PHPStan\Type\BooleanType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\UnionType;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use Ssch\TYPO3Rector\General\Renaming\ConstantsToBackedEnumRector;
use Ssch\TYPO3Rector\TYPO314\v0\AddNewMethodHasSubmoduleOverviewInModuleInterfaceRector;
use Ssch\TYPO3Rector\TYPO314\v0\ChangeLocalizationSystemArchitectureRector;
use Ssch\TYPO3Rector\TYPO314\v0\DropFifthParameterForExtensionUtilityConfigurePluginRector;
use Ssch\TYPO3Rector\TYPO314\v0\ExtendExtbaseValidatorsFromAbstractValidatorRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateAdminPanelDataProviderInterfaceRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateBooleanSortDirectionInFileListRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateButtonBarMenuAndMenuRegistryMakeMethodsToComponentFactoryRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateDataHandlerPropertiesUserIdAndAdminRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateEnvironmentGetComposerRootPathRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateGeneralUtilityCreateVersionNumberedFilenameRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateIpAnonymizationTaskRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateObsoleteCharsetInSanitizeFileNameRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigratePaletteLabelsRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateSysRedirectDefaultTypeRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateTableGarbageCollectionTaskConfigurationViaGlobalsRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateTcaTabLabelsRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateUsageOfArrayInPasswordForAuthenticationInRedisCacheBackendRector;
use Ssch\TYPO3Rector\TYPO314\v0\MoveSchedulerFrequencyOptionsToTCARector;
use Ssch\TYPO3Rector\TYPO314\v0\RemoveHttpResponseCompressionRector;
use Ssch\TYPO3Rector\TYPO314\v0\RemoveParameterInAuthenticationServiceRector;
use Ssch\TYPO3Rector\TYPO314\v0\RemoveRandomSubpageOptionRector;
use Ssch\TYPO3Rector\TYPO314\v0\RemoveRegistrationOfMetadataExtractorsRector;
use Ssch\TYPO3Rector\TYPO314\v0\ReplaceLocalizationParsersWithLoaders;
use Ssch\TYPO3Rector\TYPO314\v0\UseRecordApiInListModuleRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->ruleWithConfiguration(
        AddReturnTypeDeclarationRector::class,
        [
            // See https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-106869-RemoveStaticFunctionParameterInAuthenticationService.html
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Authentication\AuthenticationService', 'processLoginData', new UnionType([new BooleanType(), new IntegerType()])),
        ]
    );
    $rectorConfig->ruleWithConfiguration(
        ConstantsToBackedEnumRector::class,
        [
            // See https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-107648-InfoboxViewHelperStateConstants.html
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Fluid\ViewHelpers\Be\InfoboxViewHelper',
                'STATE_NOTICE',
                'TYPO3\CMS\Core\Type\ContextualFeedbackSeverity',
                'NOTICE'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Fluid\ViewHelpers\Be\InfoboxViewHelper',
                'STATE_INFO',
                'TYPO3\CMS\Core\Type\ContextualFeedbackSeverity',
                'INFO'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Fluid\ViewHelpers\Be\InfoboxViewHelper',
                'STATE_OK',
                'TYPO3\CMS\Core\Type\ContextualFeedbackSeverity',
                'OK'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Fluid\ViewHelpers\Be\InfoboxViewHelper',
                'STATE_WARNING',
                'TYPO3\CMS\Core\Type\ContextualFeedbackSeverity',
                'WARNING'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Fluid\ViewHelpers\Be\InfoboxViewHelper',
                'STATE_ERROR',
                'TYPO3\CMS\Core\Type\ContextualFeedbackSeverity',
                'ERROR'
            ),
        ]
    );
    $rectorConfig->ruleWithConfiguration(
        RenameClassRector::class,
        [
            // See https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-107229-DeprecatePhpAnnotationNamespaceOfExtbaseAttributes.html
            'TYPO3\CMS\Extbase\Annotation' => 'TYPO3\CMS\Extbase\Attribute',
        ]
    );
    $rectorConfig->ruleWithConfiguration(
        RenameMethodRector::class,
        [
            // See https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-107813-DeprecateMetaInformationAPI.html
            new MethodCallRename(
                'TYPO3\CMS\Backend\Template\Components\DocHeaderComponent',
                'setMetaInformation',
                'setPageBreadcrumb'
            ),
            new MethodCallRename(
                'TYPO3\CMS\Backend\Template\Components\DocHeaderComponent',
                'setMetaInformationForResource',
                'setResourceBreadcrumb'
            ),
        ]
    );
    $rectorConfig->rule(AddNewMethodHasSubmoduleOverviewInModuleInterfaceRector::class);
    $rectorConfig->rule(ChangeLocalizationSystemArchitectureRector::class);
    $rectorConfig->rule(DropFifthParameterForExtensionUtilityConfigurePluginRector::class);
    $rectorConfig->rule(ExtendExtbaseValidatorsFromAbstractValidatorRector::class);
    $rectorConfig->rule(MigrateAdminPanelDataProviderInterfaceRector::class);
    $rectorConfig->rule(MigrateBooleanSortDirectionInFileListRector::class);
    $rectorConfig->rule(MigrateButtonBarMenuAndMenuRegistryMakeMethodsToComponentFactoryRector::class);
    $rectorConfig->rule(MigrateDataHandlerPropertiesUserIdAndAdminRector::class);
    $rectorConfig->rule(MigrateEnvironmentGetComposerRootPathRector::class);
    $rectorConfig->rule(MigrateGeneralUtilityCreateVersionNumberedFilenameRector::class);
    $rectorConfig->rule(MigrateIpAnonymizationTaskRector::class);
    $rectorConfig->rule(MigrateObsoleteCharsetInSanitizeFileNameRector::class);
    $rectorConfig->rule(MigratePaletteLabelsRector::class);
    $rectorConfig->rule(MigrateSysRedirectDefaultTypeRector::class);
    $rectorConfig->rule(MigrateTableGarbageCollectionTaskConfigurationViaGlobalsRector::class);
    $rectorConfig->rule(MigrateTcaTabLabelsRector::class);
    $rectorConfig->rule(MigrateUsageOfArrayInPasswordForAuthenticationInRedisCacheBackendRector::class);
    $rectorConfig->rule(MoveSchedulerFrequencyOptionsToTCARector::class);
    $rectorConfig->rule(RemoveHttpResponseCompressionRector::class);
    $rectorConfig->rule(RemoveParameterInAuthenticationServiceRector::class);
    $rectorConfig->rule(RemoveRandomSubpageOptionRector::class);
    $rectorConfig->rule(RemoveRegistrationOfMetadataExtractorsRector::class);
    $rectorConfig->rule(ReplaceLocalizationParsersWithLoaders::class);
    $rectorConfig->rule(UseRecordApiInListModuleRector::class);
};
