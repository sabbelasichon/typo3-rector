<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Class_\RenameClassRector;
use Ssch\TYPO3Rector\Rector\Backend\Controller\RemovePropertiesFromSimpleDataHandlerControllerRector;
use Ssch\TYPO3Rector\Rector\Core\CheckForExtensionInfoRector;
use Ssch\TYPO3Rector\Rector\Core\CheckForExtensionVersionRector;
use Ssch\TYPO3Rector\Rector\Core\SubstituteConstantParsetimeStartRector;
use Ssch\TYPO3Rector\Rector\Core\Utility\RefactorDeprecationLogRector;
use Ssch\TYPO3Rector\Rector\Core\Utility\RefactorMethodsFromExtensionManagementUtilityRector;
use Ssch\TYPO3Rector\Rector\Core\Utility\RemoveSecondArgumentGeneralUtilityMkdirDeepRector;
use Ssch\TYPO3Rector\Rector\Fluid\ViewHelpers\UseRenderingContextGetControllerContextRector;
use Ssch\TYPO3Rector\Rector\SysNote\Domain\Repository\FindByPidsAndAuthorIdRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');

    $services = $containerConfigurator->services();

    $services->set(CheckForExtensionInfoRector::class);

    $services->set(RefactorMethodsFromExtensionManagementUtilityRector::class);

    $services->set(FindByPidsAndAuthorIdRector::class);

    $services->set(UseRenderingContextGetControllerContextRector::class);

    $services->set(RemovePropertiesFromSimpleDataHandlerControllerRector::class);

    $services->set(RenameClassRector::class)
        ->call('configure', [[
            RenameClassRector::OLD_TO_NEW_CLASSES => [
                ['TYPO3\CMS\Sv\AbstractAuthenticationService' => 'TYPO3\CMS\Core\Authentication\AbstractAuthenticationService', 'TYPO3\CMS\Sv\AuthenticationService' => 'TYPO3\CMS\Core\Authentication\AuthenticationService']
            ],
        ]]);
    $services->set(SubstituteConstantParsetimeStartRector::class);

    $services->set(RemoveSecondArgumentGeneralUtilityMkdirDeepRector::class);

    $services->set(CheckForExtensionVersionRector::class);

    //$services->set(RefactorDeprecationLogRector::class);
};
