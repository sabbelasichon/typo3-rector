<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../../../config/v11/typo3-110.php');
    $containerConfigurator->import(__DIR__ . '/../../../../../../config/config_test.php');
};
