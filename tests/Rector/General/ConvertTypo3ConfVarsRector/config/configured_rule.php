<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\General\ConvertTypo3ConfVarsRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../../config/config.php');
    $services = $containerConfigurator->services();

    $services->set(ConvertTypo3ConfVarsRector::class);
};
