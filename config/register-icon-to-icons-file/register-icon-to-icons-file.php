<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\v11\v5\RegisterIconToIconFileRector;
use Ssch\TYPO3Rector\Rector\v11\v5\RegisterIconToIconFileRector\AddIconsToReturnRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $services = $containerConfigurator->services();
    $services->set(AddIconsToReturnRector::class);
    $services->set(RegisterIconToIconFileRector::class);
};
