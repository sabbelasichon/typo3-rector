<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use function Rector\SymfonyPhpConfig\inline_value_objects;
use Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector;
use Ssch\TYPO3Rector\Rector\v9\v4\RefactorPropertiesOfTypoScriptFrontendControllerRector;

use Ssch\TYPO3Rector\Rector\v9\v5\RefactorProcessOutputRector;
use Ssch\TYPO3Rector\Rector\v9\v5\RemoveFlushCachesRector;
use Ssch\TYPO3Rector\Rector\v9\v5\RemoveInitMethodFromPageRepositoryRector;
use Ssch\TYPO3Rector\Rector\v9\v5\RemoveInternalAnnotationRector;
use Ssch\TYPO3Rector\Rector\v9\v5\UsePackageManagerActivePackagesRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');

    $services = $containerConfigurator->services();

    $services->set(UsePackageManagerActivePackagesRector::class);

    $services->set(RenameMethodRector::class)
        ->call('configure', [
            [
                RenameMethodRector::METHOD_CALL_RENAMES => inline_value_objects([
                    new MethodCallRename(
                       'TYPO3\CMS\Core\Resource\ResourceStorage',
                       'dumpFileContents',
                       'streamFile'
                   ),
                ]),
            ],
        ]);

    $services->set(RemoveFlushCachesRector::class);

    $services->set(RemoveInternalAnnotationRector::class);

    $services->set(RenameClassMapAliasRector::class)
        ->call('configure', [
            [
                RenameClassMapAliasRector::CLASS_ALIAS_MAPS => [
                    __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/adminpanel/Migrations/Code/ClassAliasMap.php',
                    __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/backend/Migrations/Code/ClassAliasMap.php',
                    __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/core/Migrations/Code/ClassAliasMap.php',
                    __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/fluid/Migrations/Code/ClassAliasMap.php',
                    __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/info/Migrations/Code/ClassAliasMap.php',
                    __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/lowlevel/Migrations/Code/ClassAliasMap.php',
                    __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/recordlist/Migrations/Code/ClassAliasMap.php',
                    __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/reports/Migrations/Code/ClassAliasMap.php',
                    __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/t3editor/Migrations/Code/ClassAliasMap.php',
                    __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/workspaces/Migrations/Code/ClassAliasMap.php',
                ],
            ],
        ]);

    $services->set(RemoveInitMethodFromPageRepositoryRector::class);

    $services->set(RefactorProcessOutputRector::class);

    $services->set(RefactorPropertiesOfTypoScriptFrontendControllerRector::class);
};
