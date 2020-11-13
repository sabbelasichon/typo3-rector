<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use function Rector\SymfonyPhpConfig\inline_value_objects;
use Ssch\TYPO3Rector\Rector\v9\v3\BackendUtilityGetModuleUrlRector;
use Ssch\TYPO3Rector\Rector\v9\v3\CopyMethodGetPidForModTSconfigRector;
use Ssch\TYPO3Rector\Rector\v9\v3\PropertyUserTsToMethodGetTsConfigOfBackendUserAuthenticationRector;
use Ssch\TYPO3Rector\Rector\v9\v3\RemoveColPosParameterRector;
use Ssch\TYPO3Rector\Rector\v9\v3\UseMethodGetPageShortcutDirectlyFromSysPageRector;
use Ssch\TYPO3Rector\Rector\v9\v3\ValidateAnnotationRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Backend\Controller\Page\LocalizationController;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');

    $services = $containerConfigurator->services();

    $services->set(RemoveColPosParameterRector::class);

    $services->set(ValidateAnnotationRector::class);

    $services->set(RenameMethodRector::class)
        ->call('configure', [[
            RenameMethodRector::METHOD_CALL_RENAMES => inline_value_objects([
                new MethodCallRename(
                    LocalizationController::class,
                    'getUsedLanguagesInPageAndColumn',
                    'getUsedLanguagesInPage'
                ),
            ]),
        ]]);

    $services->set(BackendUtilityGetModuleUrlRector::class);
    $services->set(PropertyUserTsToMethodGetTsConfigOfBackendUserAuthenticationRector::class);
    $services->set(UseMethodGetPageShortcutDirectlyFromSysPageRector::class);
    $services->set(CopyMethodGetPidForModTSconfigRector::class);
};
