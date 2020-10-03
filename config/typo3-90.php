<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Name\RenameClassRector;
use Ssch\TYPO3Rector\Rector\Backend\Controller\RemovePropertiesFromSimpleDataHandlerControllerRector;
use Ssch\TYPO3Rector\Rector\Core\CheckForExtensionInfoRector;
use Ssch\TYPO3Rector\Rector\Core\CheckForExtensionVersionRector;
use Ssch\TYPO3Rector\Rector\Core\SubstituteConstantParsetimeStartRector;
use Ssch\TYPO3Rector\Rector\Core\Utility\RefactorDeprecationLogRector;
use Ssch\TYPO3Rector\Rector\Core\Utility\RefactorMethodsFromExtensionManagementUtilityRector;
use Ssch\TYPO3Rector\Rector\Core\Utility\RemoveSecondArgumentGeneralUtilityMkdirDeepRector;
use Ssch\TYPO3Rector\Rector\Fluid\ViewHelpers\UseRenderingContextGetControllerContextRector;
use Ssch\TYPO3Rector\Rector\SysNote\Domain\Repository\FindByPidsAndAuthorIdRector;
use Ssch\TYPO3Rector\Rector\v9\v0\MetaTagManagementRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\Authentication\AbstractAuthenticationService as CoreAbstractAuthenticationService;
use TYPO3\CMS\Core\Authentication\AuthenticationService as CoreAuthenticationService;
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
};
