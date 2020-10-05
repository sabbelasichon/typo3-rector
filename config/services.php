<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Helper\Database\Refactorings\DatabaseConnectionToDbalRefactoring;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->defaults()
        ->public()
        ->autowire();

    $services
        ->instanceof(DatabaseConnectionToDbalRefactoring::class)
        ->tag('database.dbal.refactoring')
        ->share(false);

    $services->load('Ssch\TYPO3Rector\\', __DIR__ . '/../src/')
        ->exclude([__DIR__ . '/../src/Rector']);
};
