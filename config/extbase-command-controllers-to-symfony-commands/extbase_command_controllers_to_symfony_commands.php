<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\v9\v5\ExtbaseCommandControllerToSymfonyCommand\AddArgumentToSymfonyCommandRector;
use Ssch\TYPO3Rector\Rector\v9\v5\ExtbaseCommandControllerToSymfonyCommand\AddCommandsToReturnRector;
use Ssch\TYPO3Rector\Rector\v9\v5\ExtbaseCommandControllerToSymfonyCommandRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');

    $services = $containerConfigurator->services();
    $services->set(AddArgumentToSymfonyCommandRector::class);
    $services->set(AddCommandsToReturnRector::class);
    $services->set(ExtbaseCommandControllerToSymfonyCommandRector::class);
};
