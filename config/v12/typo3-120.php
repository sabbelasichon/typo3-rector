<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\FileProcessor\Resources\Files\Rector\RenameExtTypoScriptFilesFileRector;
use Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector;
use Ssch\TYPO3Rector\Rector\v12\v0\MigrateColsToSizeForTcaTypeNoneRector;
use Ssch\TYPO3Rector\Rector\v12\v0\MigrateInternalTypeRector;
use Ssch\TYPO3Rector\Rector\v12\v0\ReplacePreviewUrlMethodRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\HintNecessaryUploadedFileChangesRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\RemoveUpdateRootlineDataRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\ReplaceContentObjectRendererGetMailToWithEmailLinkBuilderRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\ReplaceTSFECheckEnableFieldsRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\ReplaceTSFEWithContextMethodsRector;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\UseCompositeExpressionStaticMethodsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(MigrateColsToSizeForTcaTypeNoneRector::class);
    $rectorConfig->rule(MigrateInternalTypeRector::class);

    $rectorConfig
        ->ruleWithConfiguration(RenameClassMapAliasRector::class, [
            __DIR__ . '/../../Migrations/TYPO3/12.0/typo3/sysext/backend/Migrations/Code/ClassAliasMap.php',
            __DIR__ . '/../../Migrations/TYPO3/12.0/typo3/sysext/frontend/Migrations/Code/ClassAliasMap.php',
        ]);
    $rectorConfig->rule(ReplacePreviewUrlMethodRector::class);
    $rectorConfig->rule(RenameExtTypoScriptFilesFileRector::class);
    $rectorConfig->rule(ReplaceTSFECheckEnableFieldsRector::class);
    $rectorConfig->rule(ReplaceContentObjectRendererGetMailToWithEmailLinkBuilderRector::class);
    $rectorConfig->rule(RemoveUpdateRootlineDataRector::class);
    $rectorConfig->rule(ReplaceTSFEWithContextMethodsRector::class);
    $rectorConfig->rule(HintNecessaryUploadedFileChangesRector::class);
    $rectorConfig->rule(UseCompositeExpressionStaticMethodsRector::class);
};
