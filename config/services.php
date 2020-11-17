<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Console\Application;
use Ssch\TYPO3Rector\Helper\Database\Refactorings\DatabaseConnectionToDbalRefactoring;
use Symfony\Component\Console\Application as SymfonyApplication;
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

    $services->alias(SymfonyApplication::class, Application::class);

    $services->load('Ssch\\TYPO3Rector\\', __DIR__ . '/../src')
        ->exclude(
            [
                __DIR__ . '/../src/Rector',
                __DIR__ . '/../src/Set',
                __DIR__ . '/../src/Bootstrap',
                __DIR__ . '/../src/DependencyInjection',
                __DIR__ . '/../src/HttpKernel',
                __DIR__ . '/../src/Compiler',
            ]
        );
};
