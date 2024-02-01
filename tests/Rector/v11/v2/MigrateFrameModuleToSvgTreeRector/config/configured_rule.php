<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO311\v2\MigrateFrameModuleToSvgTreeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config.php');
    $rectorConfig->rule(MigrateFrameModuleToSvgTreeRector::class);
};
