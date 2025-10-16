<?php

declare(strict_types=1);

use PHPStan\Type\BooleanType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\UnionType;
use Rector\Config\RectorConfig;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use Ssch\TYPO3Rector\General\Renaming\ConstantsToBackedEnumRector;
use Ssch\TYPO3Rector\TYPO314\v0\DropFifthParameterForExtensionUtilityConfigurePluginRector;
use Ssch\TYPO3Rector\TYPO314\v0\ExtendExtbaseValidatorsFromAbstractValidatorRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateAdminPanelDataProviderInterfaceRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateBooleanSortDirectionInFileListRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateEnvironmentGetComposerRootPathRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateIpAnonymizationTaskRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateObsoleteCharsetInSanitizeFileNameRector;
use Ssch\TYPO3Rector\TYPO314\v0\RemoveParameterInAuthenticationServiceRector;
use Ssch\TYPO3Rector\TYPO314\v0\UseRecordApiInListModuleRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(DropFifthParameterForExtensionUtilityConfigurePluginRector::class);
    $rectorConfig->rule(ExtendExtbaseValidatorsFromAbstractValidatorRector::class);
    $rectorConfig->rule(MigrateAdminPanelDataProviderInterfaceRector::class);
    $rectorConfig->rule(MigrateBooleanSortDirectionInFileListRector::class);
    $rectorConfig->rule(MigrateEnvironmentGetComposerRootPathRector::class);
    $rectorConfig->rule(MigrateIpAnonymizationTaskRector::class);
    $rectorConfig->rule(MigrateObsoleteCharsetInSanitizeFileNameRector::class);
    $rectorConfig->rule(RemoveParameterInAuthenticationServiceRector::class);
    $rectorConfig->ruleWithConfiguration(
        AddReturnTypeDeclarationRector::class,
        [
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Authentication\AuthenticationService', 'processLoginData', new UnionType([new BooleanType(), new IntegerType()])),
        ]
    );
    $rectorConfig->ruleWithConfiguration(
        ConstantsToBackedEnumRector::class,
        [
            // See https://github.com/sabbelasichon/typo3-rector/issues/4680
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
    $rectorConfig->rule(UseRecordApiInListModuleRector::class);
};
