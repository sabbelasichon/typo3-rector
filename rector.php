<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\PHPUnit\Rector\Class_\AddSeeTestAnnotationRector;
use Rector\Set\ValueObject\SetList;
use Ssch\TYPO3Rector\ComposerPackages\Rector\RemovePackageVersionsRector;
use Ssch\TYPO3Rector\Rules\Rector\Misc\AddCodeCoverageIgnoreToMethodRectorDefinitionRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/config/services.php');

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::PHPSTAN_FOR_RECTOR_PATH, __DIR__ . '/phpstan.neon');

//    $parameters->set(Option::SETS, [
//        SetList::PRIVATIZATION,
//        SetList::DEAD_CODE,
//        SetList::CODING_STYLE,
//        SetList::CODE_QUALITY,
//    ]);

    $services = $containerConfigurator->services();

    $services->set(\Ssch\TYPO3Rector\Rector\ObjectTypeRector::class);

//    $services->set(AddCodeCoverageIgnoreToMethodRectorDefinitionRector::class);
//    $services->set(AddSeeTestAnnotationRector::class);

    $parameters->set(Option::PATHS, [__DIR__ . '/config', __DIR__ . '/src', __DIR__ . '/tests']);

    $parameters->set(
        Option::SKIP,
        [
            RemovePackageVersionsRector::class,
            __DIR__ . '/src/Bootstrap',
            __DIR__ . '/src/Set',
            __DIR__ . '/src/Compiler',
        ]
    );

    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_73);
    $parameters->set(Option::ENABLE_CACHE, true);
};
