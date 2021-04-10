<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\v11\v0\UniqueListFromStringUtilityRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../../../config/config.php');

    $services = $containerConfigurator->services();
    $services->set(UniqueListFromStringUtilityRector::class);
};
