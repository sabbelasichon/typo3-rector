<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\ComposerPackages\Rector\RemovePackageVersionsRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::PHPSTAN_FOR_RECTOR_PATH, __DIR__ . '/phpstan.neon');

    $services = $containerConfigurator->services();

    $services->set(\Ssch\TYPO3Rector\Rector\ObjectTypeRector::class);

    $parameters->set(Option::PATHS, [__DIR__ . '/config', __DIR__ . '/src', __DIR__ . '/tests']);

    $parameters->set(
        Option::SKIP,
        [
            __DIR__ . '/src/Rector/v8/v6/RefactorTCARector.php',

            RemovePackageVersionsRector::class,
            __DIR__ . '/src/Bootstrap',
            __DIR__ . '/src/Set',
            __DIR__ . '/src/Compiler',
        ]
    );

    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_73);
    $parameters->set(Option::ENABLE_CACHE, true);
};
