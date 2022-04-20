<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\General\ExtEmConfRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../config/config_test.php');
    $rectorConfig
        ->ruleWithConfiguration(ExtEmConfRector::class, [
            ExtEmConfRector::TYPO3_VERSION_CONSTRAINT => '9.5.0-10.4.99',
            ExtEmConfRector::ADDITIONAL_VALUES_TO_BE_REMOVED => ['createDirs', 'uploadfolder'],
        ]);
};
