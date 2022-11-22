<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Ssch\TYPO3Rector\FileProcessor\Resources\Files\Rector\v12\v0\RenameExtTypoScriptFilesFileRector;
use Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector;
use Ssch\TYPO3Rector\Rector\v12\v0\ReplacePreviewUrlMethodRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\AbstractMessageGetSeverityRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\AddMethodToWidgetInterfaceClassesRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\HintNecessaryUploadedFileChangesRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\MigrateQueryBuilderExecuteRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\RemoveMailerAdapterInterfaceRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\RemoveRedundantFeLoginModeMethodsRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\RemoveRelativeToCurrentScriptArgumentsRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\RemoveTSFEConvOutputCharsetCallsRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\RemoveTSFEMetaCharSetCallsRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\RemoveUpdateRootlineDataRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\ReplaceContentObjectRendererGetMailToWithEmailLinkBuilderRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\ReplaceExpressionBuilderMethodsRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\ReplaceTSFECheckEnableFieldsRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\ReplaceTSFEWithContextMethodsRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\SubstituteCompositeExpressionAddMethodsRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\UseCompositeExpressionStaticMethodsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(RenameExtTypoScriptFilesFileRector::class);
    $rectorConfig->ruleWithConfiguration(RenameClassMapAliasRector::class, [
        __DIR__ . '/../../Migrations/TYPO3/12.0/typo3/sysext/backend/Migrations/Code/ClassAliasMap.php',
        __DIR__ . '/../../Migrations/TYPO3/12.0/typo3/sysext/frontend/Migrations/Code/ClassAliasMap.php',
    ]);
    $rectorConfig->rule(ReplacePreviewUrlMethodRector::class);
    $rectorConfig->rule(AbstractMessageGetSeverityRector::class);
    $rectorConfig->rule(AddMethodToWidgetInterfaceClassesRector::class);
    $rectorConfig->rule(HintNecessaryUploadedFileChangesRector::class);
    $rectorConfig->rule(MigrateQueryBuilderExecuteRector::class);
    $rectorConfig->rule(RemoveMailerAdapterInterfaceRector::class);
    $rectorConfig->rule(RemoveRedundantFeLoginModeMethodsRector::class);
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

    # https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/12.0/Deprecation-97787-SeveritiesOfFlashMessagesAndReportsDeprecated.html
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
        ]
    );
    # https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/12.0/Important-97809-UpdateTypo3iconsToV3.html
    $rectorConfig->ruleWithConfiguration(
        RenameClassConstFetchRector::class,
        [
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Core\Imaging\Icon',
                'SIZE_DEFAULT',
                'TYPO3\CMS\Core\Imaging\Icon',
                'SIZE_MEDIUM'
            ),
        ]
    );
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v12\v0\typo3\UseConfigArrayForTSFEPropertiesRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v12\v0\typo3\ReplacePageRepoOverlayFunctionRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v12\v0\typo3\ImplementSiteLanguageAwareInterfaceRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v12\v0\typo3\RegisterExtbaseTypeConvertersAsServicesRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\Rector\v12\v0\typo3\ChangeExtbaseValidatorsRector::class);
};
