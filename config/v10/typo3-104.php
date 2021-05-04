<?php

declare(strict_types=1);

use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector;
use Ssch\TYPO3Rector\Rector\v10\v4\SubstituteGeneralUtilityMethodsWithNativePhpFunctionsRector;
use Ssch\TYPO3Rector\Rector\v10\v4\UnifiedFileNameValidatorRector;
use Ssch\TYPO3Rector\Rector\v10\v4\UseFileGetContentsForGetUrlRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set(UnifiedFileNameValidatorRector::class);
    $services->set(SubstituteGeneralUtilityMethodsWithNativePhpFunctionsRector::class);
    $services->set('rename_static_method_is_running_on_cgi_server_api_to_is_running_on_cgi_server')
        ->class(RenameStaticMethodRector::class)
        ->call(
            'configure',
            [[
                RenameStaticMethodRector::OLD_TO_NEW_METHODS_BY_CLASSES => ValueObjectInliner::inline([
                    new RenameStaticMethod(
                        'TYPO3\CMS\Core\Utility\GeneralUtility',
                        'isRunningOnCgiServerApi',
                        'TYPO3\CMS\Core\Core\Environment',
                        'isRunningOnCgiServer'
                    ),
                ]),
            ]]
        );
    $services->set('rename_class_alias_maps_version_104')
        ->class(RenameClassMapAliasRector::class)
        ->call('configure', [[
            RenameClassMapAliasRector::CLASS_ALIAS_MAPS => [
                __DIR__ . '/../../Migrations/TYPO3/10.4/typo3/sysext/backend/Migrations/Code/ClassAliasMap.php',
                __DIR__ . '/../../Migrations/TYPO3/10.4/typo3/sysext/core/Migrations/Code/ClassAliasMap.php',
            ],
        ]]);
    $services->set(UseFileGetContentsForGetUrlRector::class);
};
