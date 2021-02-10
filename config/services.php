<?php

declare(strict_types=1);

use PHPStan\Analyser\NodeScopeResolver;
use PHPStan\Analyser\ScopeFactory;
use PHPStan\PhpDoc\TypeNodeResolver;
use PHPStan\Reflection\ReflectionProvider;
use Rector\ChangesReporting\Output\ConsoleOutputFormatter;
use Ssch\TYPO3Rector\Console\Application;
use Ssch\TYPO3Rector\Console\Output\DecoratedConsoleOutputFormatter;
use Ssch\TYPO3Rector\DependencyInjection\PHPStanServicesFactory;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../utils/**/config/config.php', null, true);

    $services = $containerConfigurator->services();
    $services->defaults()
        ->public()
        ->autowire();

    $services->alias(SymfonyApplication::class, Application::class);

    $services->set(ReflectionProvider::class)
        ->factory(
            [\Ssch\TYPO3Rector\Helper\DependencyInjection::service(
                PHPStanServicesFactory::class
            ), 'createReflectionProvider']
        );

    $services->set(NodeScopeResolver::class)
        ->factory(
            [\Ssch\TYPO3Rector\Helper\DependencyInjection::service(
                PHPStanServicesFactory::class
            ), 'createNodeScopeResolver']
        );

    $services->set(ScopeFactory::class)
        ->factory(
            [\Ssch\TYPO3Rector\Helper\DependencyInjection::service(PHPStanServicesFactory::class), 'createScopeFactory']
        );

    $services->set(TypeNodeResolver::class)
        ->factory(
            [\Ssch\TYPO3Rector\Helper\DependencyInjection::service(
                PHPStanServicesFactory::class
            ), 'createTypeNodeResolver']
        );

    $services->load('Ssch\\TYPO3Rector\\', __DIR__ . '/../src')
        ->exclude(
            [
                __DIR__ . '/../src/Rector',
                __DIR__ . '/../src/Set',
                __DIR__ . '/../src/Bootstrap',
                __DIR__ . '/../src/HttpKernel',
                __DIR__ . '/../src/Compiler',
                __DIR__ . '/../src/ValueObject',
            ]
        );

    $services->set(DecoratedConsoleOutputFormatter::class)->decorate(ConsoleOutputFormatter::class);
};
