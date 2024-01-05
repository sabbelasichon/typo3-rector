<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Filesystem\FileInfoFactory;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Filesystem\Filesystem;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->set(Filesystem::class);
    $services->set(FileInfoFactory::class);

    $services->load('Ssch\\TYPO3Rector\\Generator\\', __DIR__ . '/../src')
        ->exclude([__DIR__ . '/../src/ValueObject']);
};
