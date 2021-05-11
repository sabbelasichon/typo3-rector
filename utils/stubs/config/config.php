<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SmartFileSystem\SmartFileSystem;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->load('Ssch\TYPO3Rector\Stubs\\', __DIR__ . '/../src');
    $services->set(SmartFileSystem::class);
};
