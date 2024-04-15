<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO312\v0\MigrateRenderTypeInputLinkToTypeLinkRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->ruleWithConfiguration(MigrateRenderTypeInputLinkToTypeLinkRector::class, [
        MigrateRenderTypeInputLinkToTypeLinkRector::ALLOWED_TYPES => ['email', 'url'],
    ]);
};
