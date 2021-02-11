<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\v7\v4\MethodReadLLFileToLocalizationFactoryRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(MethodReadLLFileToLocalizationFactoryRector::class);
};
