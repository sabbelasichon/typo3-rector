<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\v8\v1\Array2XmlCsToArray2XmlRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../../../config/services.php');

    $services = $containerConfigurator->services();
    $services->set(Array2XmlCsToArray2XmlRector::class);
};
