<?php

declare(strict_types=1);

use PHPStan\Analyser\NodeScopeResolver;
use PHPStan\Analyser\ScopeFactory;
use PHPStan\PhpDoc\TypeNodeResolver;
use PHPStan\Reflection\ReflectionProvider;
use Ssch\TYPO3Rector\Console\Application;
use Ssch\TYPO3Rector\DependencyInjection\PHPStanServicesFactory;
use Ssch\TYPO3Rector\Helper\Database\Refactorings\DatabaseConnectionToDbalRefactoring;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

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

    $services->set(ReflectionProvider::class)
        ->factory([service(PHPStanServicesFactory::class), 'createReflectionProvider']);

    $services->set(NodeScopeResolver::class)
        ->factory([service(PHPStanServicesFactory::class), 'createNodeScopeResolver']);

    $services->set(ScopeFactory::class)
        ->factory([service(PHPStanServicesFactory::class), 'createScopeFactory']);

    $services->set(TypeNodeResolver::class)
        ->factory([service(PHPStanServicesFactory::class), 'createTypeNodeResolver']);

    $services->load('Ssch\\TYPO3Rector\\', __DIR__ . '/../src')
        ->exclude(
            [
                __DIR__ . '/../src/Rector',
                __DIR__ . '/../src/Set',
                __DIR__ . '/../src/Bootstrap',
                __DIR__ . '/../src/HttpKernel',
                __DIR__ . '/../src/Compiler',
            ]
        );
};
