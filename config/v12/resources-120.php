<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\v12\v0\AbstractMessageGetSeverityFluidRector;
use Ssch\TYPO3Rector\FileProcessor\Resources\Files\Rector\v12\v0\RenameConstantsAndSetupFileEndingRector;
use Ssch\TYPO3Rector\FileProcessor\Resources\Files\Rector\v12\v0\RenameExtTypoScriptFilesFileRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->services()
        ->set(AbstractMessageGetSeverityFluidRector::class)->tag('typo3_rector.fluid_rectors');
    $rectorConfig->rule(RenameConstantsAndSetupFileEndingRector::class);
    $rectorConfig->rule(RenameExtTypoScriptFilesFileRector::class);
};
