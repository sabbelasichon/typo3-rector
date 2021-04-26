<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;

use Rector\Renaming\ValueObject\MethodCallRename;
use Ssch\TYPO3Rector\FlexForms\Transformer\RenderTypeTransformer;
use Ssch\TYPO3Rector\Rector\Composer\RemoveCmsPackageDirFromExtraComposerRector;
use Ssch\TYPO3Rector\Rector\v9\v0\CheckForExtensionInfoRector;
use Ssch\TYPO3Rector\Rector\v9\v0\CheckForExtensionVersionRector;
use Ssch\TYPO3Rector\Rector\v9\v0\FindByPidsAndAuthorIdRector;
use Ssch\TYPO3Rector\Rector\v9\v0\GeneratePageTitleRector;
use Ssch\TYPO3Rector\Rector\v9\v0\IgnoreValidationAnnotationRector;
use Ssch\TYPO3Rector\Rector\v9\v0\InjectAnnotationRector;
use Ssch\TYPO3Rector\Rector\v9\v0\MetaTagManagementRector;
use Ssch\TYPO3Rector\Rector\v9\v0\MoveRenderArgumentsToInitializeArgumentsMethodRector;
use Ssch\TYPO3Rector\Rector\v9\v0\RefactorBackendUtilityGetPagesTSconfigRector;
use Ssch\TYPO3Rector\Rector\v9\v0\RefactorDeprecationLogRector;
use Ssch\TYPO3Rector\Rector\v9\v0\RefactorMethodsFromExtensionManagementUtilityRector;
use Ssch\TYPO3Rector\Rector\v9\v0\RemoveMethodInitTCARector;
use Ssch\TYPO3Rector\Rector\v9\v0\RemovePropertiesFromSimpleDataHandlerControllerRector;
use Ssch\TYPO3Rector\Rector\v9\v0\RemoveSecondArgumentGeneralUtilityMkdirDeepRector;
use Ssch\TYPO3Rector\Rector\v9\v0\ReplaceAnnotationRector;
use Ssch\TYPO3Rector\Rector\v9\v0\ReplacedGeneralUtilitySysLogWithLogginApiRector;
use Ssch\TYPO3Rector\Rector\v9\v0\ReplaceExtKeyWithExtensionKeyRector;
use Ssch\TYPO3Rector\Rector\v9\v0\SubstituteCacheWrapperMethodsRector;
use Ssch\TYPO3Rector\Rector\v9\v0\SubstituteConstantParsetimeStartRector;
use Ssch\TYPO3Rector\Rector\v9\v0\SubstituteGeneralUtilityDevLogRector;
use Ssch\TYPO3Rector\Rector\v9\v0\UseExtensionConfigurationApiRector;
use Ssch\TYPO3Rector\Rector\v9\v0\UseLogMethodInsteadOfNewLog2Rector;
use Ssch\TYPO3Rector\Rector\v9\v0\UseNewComponentIdForPageTreeRector;
use Ssch\TYPO3Rector\Rector\v9\v0\UseRenderingContextGetControllerContextRector;
use Ssch\TYPO3Rector\TypoScript\Visitors\FileIncludeToImportStatementVisitor;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
use TYPO3\CMS\Core\Utility\GeneralUtility;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set(MoveRenderArgumentsToInitializeArgumentsMethodRector::class);
    $services->set(InjectAnnotationRector::class);
    $services->set(IgnoreValidationAnnotationRector::class);
    $services->set('replace_extbase_annotations_to_doctrine_annotations')
        ->class(ReplaceAnnotationRector::class)
        ->call('configure', [[
            ReplaceAnnotationRector::OLD_TO_NEW_ANNOTATIONS => [
                'lazy' => 'TYPO3\CMS\Extbase\Annotation\ORM\Lazy',
                'cascade' => 'TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")',
                'transient' => 'TYPO3\CMS\Extbase\Annotation\ORM\Transient',

            ],
        ]]);
    $services->set(CheckForExtensionInfoRector::class);
    $services->set(RefactorMethodsFromExtensionManagementUtilityRector::class);
    $services->set(MetaTagManagementRector::class);
    $services->set(FindByPidsAndAuthorIdRector::class);
    $services->set(UseRenderingContextGetControllerContextRector::class);
    $services->set(RemovePropertiesFromSimpleDataHandlerControllerRector::class);
    $services->set(RemoveMethodInitTCARector::class);
    $services->set(SubstituteCacheWrapperMethodsRector::class);
    $services->set(UseLogMethodInsteadOfNewLog2Rector::class);
    $services->set(GeneratePageTitleRector::class);
    $services->set(SubstituteConstantParsetimeStartRector::class);
    $services->set(RemoveSecondArgumentGeneralUtilityMkdirDeepRector::class);
    $services->set(CheckForExtensionVersionRector::class);
    $services->set(RefactorDeprecationLogRector::class);
    $services->set('general_utility_get_user_obj_to_make_instance')
        ->class(RenameMethodRector::class)
        ->call('configure', [[
            RenameMethodRector::METHOD_CALL_RENAMES => ValueObjectInliner::inline([
                new MethodCallRename(GeneralUtility::class, 'getUserObj', 'makeInstance'),
            ]),
        ]]);
    $services->set(UseNewComponentIdForPageTreeRector::class);
    $services->set(RefactorBackendUtilityGetPagesTSconfigRector::class);
    $services->set(UseExtensionConfigurationApiRector::class);
    $services->set(ReplaceExtKeyWithExtensionKeyRector::class);
    $services->set(RemoveCmsPackageDirFromExtraComposerRector::class);
    $services->set(SubstituteGeneralUtilityDevLogRector::class);
    $services->set(ReplacedGeneralUtilitySysLogWithLogginApiRector::class);
    $services->set(RenderTypeTransformer::class);
    # $services->set(FileIncludeToImportStatementVisitor::class);
    # $services->set(FileIncludeToImportStatementVisitor::class);
};
