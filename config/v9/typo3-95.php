<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector;
use Ssch\TYPO3Rector\Rector\v9\v5\RefactorPropertiesOfTypoScriptFrontendControllerRector;
use Ssch\TYPO3Rector\Rector\v9\v5\RemoveFlushCachesRector;
use Ssch\TYPO3Rector\Rector\v9\v5\RemoveInitMethodFromPageRepositoryRector;
use Ssch\TYPO3Rector\Rector\v9\v5\RemoveInternalAnnotationRector;
use Ssch\TYPO3Rector\Rector\v9\v5\UsePackageManagerActivePackagesRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(UsePackageManagerActivePackagesRector::class);
    $rectorConfig
        ->ruleWithConfiguration(RenameMethodRector::class, [
            new MethodCallRename('TYPO3\CMS\Core\Resource\ResourceStorage', 'dumpFileContents', 'streamFile'),
        ]);
    $rectorConfig->rule(RemoveFlushCachesRector::class);
    $rectorConfig->rule(RemoveInternalAnnotationRector::class);
    $rectorConfig
        ->ruleWithConfiguration(RenameClassMapAliasRector::class, [
            __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/adminpanel/Migrations/Code/ClassAliasMap.php',
            __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/backend/Migrations/Code/ClassAliasMap.php',
            __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/core/Migrations/Code/ClassAliasMap.php',
            __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/extbase/Migrations/Code/ClassAliasMap.php',
            __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/fluid/Migrations/Code/ClassAliasMap.php',
            __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/info/Migrations/Code/ClassAliasMap.php',
            __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/lowlevel/Migrations/Code/ClassAliasMap.php',
            __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/recordlist/Migrations/Code/ClassAliasMap.php',
            __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/reports/Migrations/Code/ClassAliasMap.php',
            __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/t3editor/Migrations/Code/ClassAliasMap.php',
            __DIR__ . '/../../Migrations/TYPO3/9.5/typo3/sysext/workspaces/Migrations/Code/ClassAliasMap.php',
        ]);
    $rectorConfig->rule(RemoveInitMethodFromPageRepositoryRector::class);
    $rectorConfig->rule(RefactorPropertiesOfTypoScriptFrontendControllerRector::class);
};
