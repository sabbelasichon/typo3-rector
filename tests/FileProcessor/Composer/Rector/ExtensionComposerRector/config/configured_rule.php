<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

use Ssch\TYPO3Rector\FileProcessor\Composer\Rector\ExtensionComposerRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');

    $rectorConfig
        ->ruleWithConfiguration(ExtensionComposerRector::class, [
            ExtensionComposerRector::TYPO3_VERSION_CONSTRAINT => '^9.5',
        ]);
};
