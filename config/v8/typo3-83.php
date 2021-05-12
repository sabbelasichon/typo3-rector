<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\Resources\IconsRector;
use Ssch\TYPO3Rector\Rector\v8\v3\RefactorMethodFileContentRector;
use Ssch\TYPO3Rector\Rector\v8\v3\RefactorQueryViewTableWrapRector;
use Ssch\TYPO3Rector\Resources\Icons\IconsProcessor;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set(RefactorMethodFileContentRector::class);
    $services->set(RefactorQueryViewTableWrapRector::class);
    $services->set(IconsRector::class);
    $services->set(IconsProcessor::class)->autowire();
};
