<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Ssch\TYPO3Rector\CodeQuality\General\RenameClassMapAliasRector;
use Ssch\TYPO3Rector\TYPO312\v0\AddMethodToWidgetInterfaceClassesRector;
use Ssch\TYPO3Rector\TYPO312\v0\ChangeExtbaseValidatorsRector;
use Ssch\TYPO3Rector\TYPO312\v0\ExtbaseActionsWithRedirectMustReturnResponseInterfaceRector;
use Ssch\TYPO3Rector\TYPO312\v0\ImplementSiteLanguageAwareInterfaceRector;
use Ssch\TYPO3Rector\TYPO312\v0\MigrateBackendModuleRegistrationRector;
use Ssch\TYPO3Rector\TYPO312\v0\MigrateContentObjectRendererGetTypoLinkUrlRector;
use Ssch\TYPO3Rector\TYPO312\v0\MigrateContentObjectRendererLastTypoLinkPropertiesRector;
use Ssch\TYPO3Rector\TYPO312\v0\MigrateFetchAllToFetchAllAssociativeRector;
use Ssch\TYPO3Rector\TYPO312\v0\MigrateFetchColumnToFetchOneRector;
use Ssch\TYPO3Rector\TYPO312\v0\MigrateFetchToFetchAssociativeRector;
use Ssch\TYPO3Rector\TYPO312\v0\MigrateGetControllerContextGetUriBuilderRector;
use Ssch\TYPO3Rector\TYPO312\v0\MigrateQueryBuilderExecuteRector;
use Ssch\TYPO3Rector\TYPO312\v0\MoveAllowTableOnStandardPagesToTCAConfigurationRector;
use Ssch\TYPO3Rector\TYPO312\v0\RemoveMailerAdapterInterfaceRector;
use Ssch\TYPO3Rector\TYPO312\v0\RemoveRelativeToCurrentScriptArgumentsRector;
use Ssch\TYPO3Rector\TYPO312\v0\RemoveTSFEConvOutputCharsetCallsRector;
use Ssch\TYPO3Rector\TYPO312\v0\RemoveTSFEMetaCharSetCallsRector;
use Ssch\TYPO3Rector\TYPO312\v0\RemoveUpdateRootlineDataRector;
use Ssch\TYPO3Rector\TYPO312\v0\ReplaceContentObjectRendererGetMailToWithEmailLinkBuilderRector;
use Ssch\TYPO3Rector\TYPO312\v0\ReplaceExpressionBuilderMethodsRector;
use Ssch\TYPO3Rector\TYPO312\v0\ReplacePageRepoOverlayFunctionRector;
use Ssch\TYPO3Rector\TYPO312\v0\ReplaceTSFECheckEnableFieldsRector;
use Ssch\TYPO3Rector\TYPO312\v0\ReplaceTSFEWithContextMethodsRector;
use Ssch\TYPO3Rector\TYPO312\v0\SubstituteCompositeExpressionAddMethodsRector;
use Ssch\TYPO3Rector\TYPO312\v0\UseCompositeExpressionStaticMethodsRector;
use Ssch\TYPO3Rector\TYPO312\v0\UseConfigArrayForTSFEPropertiesRector;
use Ssch\TYPO3Rector\TYPO312\v0\UsePageDoktypeRegistryRector;
use Ssch\TYPO3Rector\TYPO312\v0\UseServerRequestInsteadOfGeneralUtilityPostRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameClassMapAliasRector::class, [
        __DIR__ . '/../../Migrations/TYPO3/12.0/typo3/sysext/backend/Migrations/Code/ClassAliasMap.php',
        __DIR__ . '/../../Migrations/TYPO3/12.0/typo3/sysext/frontend/Migrations/Code/ClassAliasMap.php',
    ]);
    $rectorConfig->rule(AddMethodToWidgetInterfaceClassesRector::class);
    $rectorConfig->rule(MigrateQueryBuilderExecuteRector::class);
    $rectorConfig->rule(RemoveMailerAdapterInterfaceRector::class);
    $rectorConfig->rule(RemoveRelativeToCurrentScriptArgumentsRector::class);
    $rectorConfig->rule(RemoveTSFEConvOutputCharsetCallsRector::class);
    $rectorConfig->rule(RemoveTSFEMetaCharSetCallsRector::class);
    $rectorConfig->rule(RemoveUpdateRootlineDataRector::class);
    $rectorConfig->rule(ReplaceContentObjectRendererGetMailToWithEmailLinkBuilderRector::class);
    $rectorConfig->rule(ReplaceExpressionBuilderMethodsRector::class);
    $rectorConfig->rule(ReplaceTSFECheckEnableFieldsRector::class);
    $rectorConfig->rule(ReplaceTSFEWithContextMethodsRector::class);
    $rectorConfig->rule(SubstituteCompositeExpressionAddMethodsRector::class);
    $rectorConfig->rule(UseCompositeExpressionStaticMethodsRector::class);

    # https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-97787-SeveritiesOfFlashMessagesAndReportsDeprecated.html
    $rectorConfig->ruleWithConfiguration(
        RenameClassConstFetchRector::class,
        [
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Core\Messaging\AbstractMessage',
                'NOTICE',
                'TYPO3\CMS\Core\Type\ContextualFeedbackSeverity',
                'NOTICE'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Core\Messaging\AbstractMessage',
                'INFO',
                'TYPO3\CMS\Core\Type\ContextualFeedbackSeverity',
                'INFO'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Core\Messaging\AbstractMessage',
                'OK',
                'TYPO3\CMS\Core\Type\ContextualFeedbackSeverity',
                'OK'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Core\Messaging\AbstractMessage',
                'WARNING',
                'TYPO3\CMS\Core\Type\ContextualFeedbackSeverity',
                'WARNING'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Core\Messaging\AbstractMessage',
                'ERROR',
                'TYPO3\CMS\Core\Type\ContextualFeedbackSeverity',
                'ERROR'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Core\Messaging\FlashMessage',
                'NOTICE',
                'TYPO3\CMS\Core\Type\ContextualFeedbackSeverity',
                'NOTICE'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Core\Messaging\FlashMessage',
                'INFO',
                'TYPO3\CMS\Core\Type\ContextualFeedbackSeverity',
                'INFO'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Core\Messaging\FlashMessage',
                'OK',
                'TYPO3\CMS\Core\Type\ContextualFeedbackSeverity',
                'OK'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Core\Messaging\FlashMessage',
                'WARNING',
                'TYPO3\CMS\Core\Type\ContextualFeedbackSeverity',
                'WARNING'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Core\Messaging\FlashMessage',
                'ERROR',
                'TYPO3\CMS\Core\Type\ContextualFeedbackSeverity',
                'ERROR'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Reports\Status',
                'NOTICE',
                'TYPO3\CMS\Core\Type\ContextualFeedbackSeverity',
                'NOTICE'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Reports\Status',
                'INFO',
                'TYPO3\CMS\Core\Type\ContextualFeedbackSeverity',
                'INFO'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Reports\Status',
                'OK',
                'TYPO3\CMS\Core\Type\ContextualFeedbackSeverity',
                'OK'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Reports\Status',
                'WARNING',
                'TYPO3\CMS\Core\Type\ContextualFeedbackSeverity',
                'WARNING'
            ),
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Reports\Status',
                'ERROR',
                'TYPO3\CMS\Core\Type\ContextualFeedbackSeverity',
                'ERROR'
            ),
            # https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Important-97809-UpdateTypo3iconsToV3.html
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Core\Imaging\Icon',
                'SIZE_DEFAULT',
                'TYPO3\CMS\Core\Imaging\Icon',
                'SIZE_MEDIUM'
            ),
        ]
    );

    $rectorConfig->rule(UseConfigArrayForTSFEPropertiesRector::class);
    $rectorConfig->rule(ReplacePageRepoOverlayFunctionRector::class);
    $rectorConfig->rule(ImplementSiteLanguageAwareInterfaceRector::class);
    $rectorConfig->rule(ChangeExtbaseValidatorsRector::class);
    $rectorConfig->rule(MigrateContentObjectRendererLastTypoLinkPropertiesRector::class);
    $rectorConfig->rule(UsePageDoktypeRegistryRector::class);
    $rectorConfig->rule(UseServerRequestInsteadOfGeneralUtilityPostRector::class);
    $rectorConfig->rule(MoveAllowTableOnStandardPagesToTCAConfigurationRector::class);
    $rectorConfig->rule(MigrateFetchColumnToFetchOneRector::class);
    $rectorConfig->rule(MigrateFetchAllToFetchAllAssociativeRector::class);
    $rectorConfig->rule(MigrateFetchToFetchAssociativeRector::class);
    $rectorConfig->rule(MigrateBackendModuleRegistrationRector::class);
    $rectorConfig->rule(MigrateContentObjectRendererGetTypoLinkUrlRector::class);
    $rectorConfig->rule(ExtbaseActionsWithRedirectMustReturnResponseInterfaceRector::class);
    $rectorConfig->rule(MigrateGetControllerContextGetUriBuilderRector::class);
};
