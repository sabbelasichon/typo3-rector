<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs\ReplaceExtensionPathRelativeFluidRector;
use Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs\ReplaceFormatJsonEncodeFluidRector;
use Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs\ReplaceLFluidRector;
use Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs\ReplaceMediaImageFluidRector;
use Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs\ReplaceOrFluidRector;
use Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs\ReplaceUriImageFluidRector;
use Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs\ReplaceVariableSetFluidRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/config.php');
    $rectorConfig->rule(ReplaceExtensionPathRelativeFluidRector::class);
    $rectorConfig->rule(ReplaceFormatJsonEncodeFluidRector::class);
    $rectorConfig->rule(ReplaceLFluidRector::class);
    $rectorConfig->rule(ReplaceMediaImageFluidRector::class);
    $rectorConfig->rule(ReplaceOrFluidRector::class);
    $rectorConfig->rule(ReplaceUriImageFluidRector::class);
    $rectorConfig->rule(ReplaceVariableSetFluidRector::class);
};
