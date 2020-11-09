<?php

declare(strict_types=1);

use OndraM\CiDetector\CiDetector;
use Ssch\TYPO3Rector\Compiler\ValueObject\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\SmartFileSystem\SmartFileSystem;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::DATA_DIR, __DIR__ . '/../build');
    $parameters->set(Option::BUILD_DIR, __DIR__ . '/..');

    $services = $containerConfigurator->services();

    $services->defaults()
        ->public()
        ->autowire();

    $services->load('Ssch\TYPO3Rector\Compiler\\', __DIR__ . '/../src/Compiler')
        ->exclude([
            __DIR__ . '/../src/Compiler/Exception',
            __DIR__ . '/../src/Compiler/DependencyInjection',
            __DIR__ . '/../src/Compiler/HttpKernel',
            __DIR__ . '/../src/Compiler/PhpScoper',
            __DIR__ . '/../src/Compiler/ValueObject',
        ]);

    $services->set(SmartFileSystem::class);

    $services->set(CiDetector::class);

    $services->set(ParameterProvider::class);
};
