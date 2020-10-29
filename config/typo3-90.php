<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use function Rector\SymfonyPhpConfig\inline_value_objects;
use Ssch\TYPO3Rector\Rector\v9\v0\CheckForExtensionInfoRector;
use Ssch\TYPO3Rector\Rector\v9\v0\CheckForExtensionVersionRector;
use Ssch\TYPO3Rector\Rector\v9\v0\FindByPidsAndAuthorIdRector;
use Ssch\TYPO3Rector\Rector\v9\v0\GeneratePageTitleRector;
use Ssch\TYPO3Rector\Rector\v9\v0\MetaTagManagementRector;
use Ssch\TYPO3Rector\Rector\v9\v0\RefactorDeprecationLogRector;
use Ssch\TYPO3Rector\Rector\v9\v0\RefactorMethodsFromExtensionManagementUtilityRector;
use Ssch\TYPO3Rector\Rector\v9\v0\RemoveMethodInitTCARector;
use Ssch\TYPO3Rector\Rector\v9\v0\RemovePropertiesFromSimpleDataHandlerControllerRector;
use Ssch\TYPO3Rector\Rector\v9\v0\RemoveSecondArgumentGeneralUtilityMkdirDeepRector;
use Ssch\TYPO3Rector\Rector\v9\v0\SubstituteCacheWrapperMethodsRector;
use Ssch\TYPO3Rector\Rector\v9\v0\SubstituteConstantParsetimeStartRector;
use Ssch\TYPO3Rector\Rector\v9\v0\UseLogMethodInsteadOfNewLog2Rector;
use Ssch\TYPO3Rector\Rector\v9\v0\UseRenderingContextGetControllerContextRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\Authentication\AbstractAuthenticationService as CoreAbstractAuthenticationService;
use TYPO3\CMS\Core\Authentication\AuthenticationService as CoreAuthenticationService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Sv\AbstractAuthenticationService;
use TYPO3\CMS\Sv\AuthenticationService;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');

    $services = $containerConfigurator->services();

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

    $services->set(RenameClassRector::class)
        ->call('configure', [[
            RenameClassRector::OLD_TO_NEW_CLASSES => [
                AbstractAuthenticationService::class => CoreAbstractAuthenticationService::class,
                AuthenticationService::class => CoreAuthenticationService::class,
            ],
        ]]);

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
};
