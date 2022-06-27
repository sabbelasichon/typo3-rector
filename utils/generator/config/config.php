<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Symfony\Component\Filesystem\Filesystem;

return static function (RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->set(Filesystem::class);

    $services->load('Ssch\TYPO3Rector\Generator\\', __DIR__ . '/../src')
        ->exclude([__DIR__ . '/../src/ValueObject']);
};
