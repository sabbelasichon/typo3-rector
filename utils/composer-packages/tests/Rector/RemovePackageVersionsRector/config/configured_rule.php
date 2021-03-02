<?php

use Ssch\TYPO3Rector\ComposerPackages\Rector\RemovePackageVersionsRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../config/config.php');

    $services = $containerConfigurator->services();

    $services->set(RemovePackageVersionsRector::class);
};
