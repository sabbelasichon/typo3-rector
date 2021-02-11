<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\v9\v0\MetaTagManagementRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(MetaTagManagementRector::class);
};
