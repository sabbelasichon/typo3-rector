<?php

declare(strict_types=1);

use PhpParser\PrettyPrinter\Standard;
use Rector\RectorGenerator\FileSystem\ConfigFilesystem;
use Rector\RectorGenerator\TemplateFactory;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SmartFileSystem\FileSystemGuard;
use Symplify\SmartFileSystem\Finder\FinderSanitizer;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->load('Ssch\TYPO3Rector\Generator\\', __DIR__ . '/../src')
        ->exclude([__DIR__ . '/../src/ValueObject']);
    $services->set(Standard::class);
    $services->set(ConfigFilesystem::class);
    $services->set(FinderSanitizer::class);
    $services->set(FileSystemGuard::class);
    $services->set(TemplateFactory::class);
};
