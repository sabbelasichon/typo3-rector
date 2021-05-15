<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\v7\v6\RenamePiListBrowserResultsRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../../../config/config_test.php');

    $services = $containerConfigurator->services();
    $services->set(RenamePiListBrowserResultsRector::class);
};
