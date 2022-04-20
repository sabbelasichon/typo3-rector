<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->import(__DIR__ . '/v8/*');
};
