<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v11\v0\tca\RemoveWorkspacePlaceholderShadowColumnsConfigurationRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(RemoveWorkspacePlaceholderShadowColumnsConfigurationRector::class);
};
