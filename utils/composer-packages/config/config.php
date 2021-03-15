<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\ComposerPackages\Rector\AddPackageVersionRector;
use Ssch\TYPO3Rector\ComposerPackages\Rector\AddReplacePackageRector;
use Ssch\TYPO3Rector\ComposerPackages\Rector\RemovePackageVersionsRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->load('Ssch\TYPO3Rector\ComposerPackages\\', __DIR__ . '/../src')
        ->exclude([__DIR__ . '/../src/Rector', __DIR__ . '/../src/ValueObject', __DIR__ . '/../src/Collection']);

    $services = $containerConfigurator->services();
    $services->set(RemovePackageVersionsRector::class);
    $services->set(AddPackageVersionRector::class);
    $services->set(AddReplacePackageRector::class);
};
