<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Ssch\TYPO3Rector\Rector\v9\v0\CheckForExtensionInfoRector;
use Ssch\TYPO3Rector\Rector\v9\v0\CheckForExtensionVersionRector;
use Ssch\TYPO3Rector\Rector\v9\v0\FindByPidsAndAuthorIdRector;
use Ssch\TYPO3Rector\Rector\v9\v0\GeneratePageTitleRector;
use Ssch\TYPO3Rector\Rector\v9\v0\IgnoreValidationAnnotationRector;
use Ssch\TYPO3Rector\Rector\v9\v0\InjectAnnotationRector;
use Ssch\TYPO3Rector\Rector\v9\v0\MetaTagManagementRector;
use Ssch\TYPO3Rector\Rector\v9\v0\MoveRenderArgumentsToInitializeArgumentsMethodRector;
use Ssch\TYPO3Rector\Rector\v9\v0\QueryLogicalOrAndLogicalAndToArrayParameterRector;
use Ssch\TYPO3Rector\Rector\v9\v0\RefactorBackendUtilityGetPagesTSconfigRector;
use Ssch\TYPO3Rector\Rector\v9\v0\RefactorDeprecationLogRector;
use Ssch\TYPO3Rector\Rector\v9\v0\RefactorMethodsFromExtensionManagementUtilityRector;
use Ssch\TYPO3Rector\Rector\v9\v0\RemoveMethodInitTCARector;
use Ssch\TYPO3Rector\Rector\v9\v0\RemovePropertiesFromSimpleDataHandlerControllerRector;
use Ssch\TYPO3Rector\Rector\v9\v0\RemoveSecondArgumentGeneralUtilityMkdirDeepRector;
use Ssch\TYPO3Rector\Rector\v9\v0\ReplaceAnnotationRector;
use Ssch\TYPO3Rector\Rector\v9\v0\ReplacedGeneralUtilitySysLogWithLogginApiRector;
use Ssch\TYPO3Rector\Rector\v9\v0\ReplaceExtKeyWithExtensionKeyRector;
use Ssch\TYPO3Rector\Rector\v9\v0\SubstituteConstantParsetimeStartRector;
use Ssch\TYPO3Rector\Rector\v9\v0\SubstituteGeneralUtilityDevLogRector;
use Ssch\TYPO3Rector\Rector\v9\v0\UseExtensionConfigurationApiRector;
use Ssch\TYPO3Rector\Rector\v9\v0\UseNewComponentIdForPageTreeRector;
use Ssch\TYPO3Rector\Rector\v9\v0\UseRenderingContextGetControllerContextRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(MoveRenderArgumentsToInitializeArgumentsMethodRector::class);
    $rectorConfig->rule(InjectAnnotationRector::class);
    $rectorConfig->rule(IgnoreValidationAnnotationRector::class);
    $rectorConfig
        ->ruleWithConfiguration(ReplaceAnnotationRector::class, [
            'lazy' => 'TYPO3\CMS\Extbase\Annotation\ORM\Lazy',
            'cascade' => 'TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")',
            'transient' => 'TYPO3\CMS\Extbase\Annotation\ORM\Transient',
        ]);
    $rectorConfig->rule(CheckForExtensionInfoRector::class);
    $rectorConfig->rule(RefactorMethodsFromExtensionManagementUtilityRector::class);
    $rectorConfig->rule(MetaTagManagementRector::class);
    $rectorConfig->rule(FindByPidsAndAuthorIdRector::class);
    $rectorConfig->rule(UseRenderingContextGetControllerContextRector::class);
    $rectorConfig->rule(RemovePropertiesFromSimpleDataHandlerControllerRector::class);
    $rectorConfig->rule(RemoveMethodInitTCARector::class);
    $rectorConfig->rule(GeneratePageTitleRector::class);
    $rectorConfig->rule(SubstituteConstantParsetimeStartRector::class);
    $rectorConfig->rule(RemoveSecondArgumentGeneralUtilityMkdirDeepRector::class);
    $rectorConfig->rule(CheckForExtensionVersionRector::class);
    $rectorConfig->rule(RefactorDeprecationLogRector::class);
    $rectorConfig
        ->ruleWithConfiguration(
            RenameMethodRector::class,
            [new MethodCallRename('TYPO3\CMS\Core\Utility\GeneralUtility', 'getUserObj', 'makeInstance')]
        );
    $rectorConfig->rule(UseNewComponentIdForPageTreeRector::class);
    $rectorConfig->rule(RefactorBackendUtilityGetPagesTSconfigRector::class);
    $rectorConfig->rule(UseExtensionConfigurationApiRector::class);
    $rectorConfig->rule(ReplaceExtKeyWithExtensionKeyRector::class);
    $rectorConfig->rule(SubstituteGeneralUtilityDevLogRector::class);
    $rectorConfig->rule(ReplacedGeneralUtilitySysLogWithLogginApiRector::class);
    $rectorConfig->rule(QueryLogicalOrAndLogicalAndToArrayParameterRector::class);
};
