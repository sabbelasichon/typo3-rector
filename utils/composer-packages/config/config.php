<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();
    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->load('Ssch\TYPO3Rector\ComposerPackages\\', __DIR__ . '/../src')
        ->exclude([__DIR__ . '/../src/ValueObject', __DIR__ . '/../src/Collection']);
};
