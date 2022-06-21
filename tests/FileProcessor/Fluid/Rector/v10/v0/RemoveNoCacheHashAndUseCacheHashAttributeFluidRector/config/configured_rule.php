<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\v10\v0\RemoveNoCacheHashAndUseCacheHashAttributeFluidRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../../../config/config_test.php');
    $rectorConfig->rule(RemoveNoCacheHashAndUseCacheHashAttributeFluidRector::class);
};
