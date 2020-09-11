<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\Backend\Utility\BackendUtilityEditOnClickRector;
use Ssch\TYPO3Rector\Rector\Extbase\RegisterPluginWithVendorNameRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');

    $services = $containerConfigurator->services();

    $services->set(RegisterPluginWithVendorNameRector::class);

    $services->set(BackendUtilityEditOnClickRector::class);
};
