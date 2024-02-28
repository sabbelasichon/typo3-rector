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
    $services = $rectorConfig->services();

    $services->set(ReplaceExtensionPathRelativeFluidRector::class)->tag('typo3_rector.fluid_rectors');
    $services->set(ReplaceFormatJsonEncodeFluidRector::class)->tag('typo3_rector.fluid_rectors');
    $services->set(ReplaceLFluidRector::class)->tag('typo3_rector.fluid_rectors');
    $services->set(ReplaceMediaImageFluidRector::class)->tag('typo3_rector.fluid_rectors');
    $services->set(ReplaceOrFluidRector::class)->tag('typo3_rector.fluid_rectors');
    $services->set(ReplaceUriImageFluidRector::class)->tag('typo3_rector.fluid_rectors');
    $services->set(ReplaceVariableSetFluidRector::class)->tag('typo3_rector.fluid_rectors');
};
