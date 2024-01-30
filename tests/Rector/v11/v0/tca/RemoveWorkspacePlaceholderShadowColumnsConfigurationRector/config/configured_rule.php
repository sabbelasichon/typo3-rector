<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\TYPO311\v0\RemoveWorkspacePlaceholderShadowColumnsConfigurationRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../../config/config_test.php');
    $rectorConfig->rule(RemoveWorkspacePlaceholderShadowColumnsConfigurationRector::class);
};
