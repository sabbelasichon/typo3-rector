<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\FileProcessor\Resources\Icons\IconsProcessor;
use Ssch\TYPO3Rector\FileProcessor\Resources\Icons\Rector\IconsRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../../../config/config_test.php');
    $services = $containerConfigurator->services();
    $services->set(IconsRector::class);
    $services->set(IconsProcessor::class)->autowire();
};
