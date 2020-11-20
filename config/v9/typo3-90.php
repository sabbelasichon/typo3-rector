<?php

declare(strict_types=1);

use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use function Rector\SymfonyPhpConfig\inline_value_objects;
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
use Ssch\TYPO3Rector\Rector\v9\v0\SubstituteCacheWrapperMethodsRector;
use Ssch\TYPO3Rector\Rector\v9\v0\SubstituteConstantParsetimeStartRector;
use Ssch\TYPO3Rector\Rector\v9\v0\UseLogMethodInsteadOfNewLog2Rector;
use Ssch\TYPO3Rector\Rector\v9\v0\UseNewComponentIdForPageTreeRector;
use Ssch\TYPO3Rector\Rector\v9\v0\UseRenderingContextGetControllerContextRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\Utility\GeneralUtility;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');

    $services = $containerConfigurator->services();

    $services->set(MoveRenderArgumentsToInitializeArgumentsMethodRector::class);

    $services->set(StringClassNameToClassConstantRector::class);

    $services->set(InjectAnnotationRector::class);

    $services->set(IgnoreValidationAnnotationRector::class);

    $services->set(ReplaceAnnotationRector::class)
        ->call('configure', [
            [
                ReplaceAnnotationRector::OLD_TO_NEW_ANNOTATIONS => [
                    'lazy' => 'TYPO3\CMS\Extbase\Annotation\ORM\Lazy',
                    'cascade' => 'TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")',
                    'transient' => 'TYPO3\CMS\Extbase\Annotation\ORM\Transient',
                ],
            ],
        ]);

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

    $services->set(RenameMethodRector::class)
        ->call(
                 'configure',
                 [[
                     RenameMethodRector::METHOD_CALL_RENAMES => inline_value_objects(
                         [new MethodCallRename(GeneralUtility::class, 'getUserObj', 'makeInstance')]
                     ),
                 ]]
             );

    $services->set(UseNewComponentIdForPageTreeRector::class);

    $services->set(RefactorBackendUtilityGetPagesTSconfigRector::class);
};
