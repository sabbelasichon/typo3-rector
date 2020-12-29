<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use SlevomatCodingStandard\Sniffs\Namespaces\ReferenceUsedNamesOnlySniff;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::SETS, [
        SetList::PSR_12,
        SetList::PHP_70,
        SetList::PHP_71,
        SetList::COMMON,
        SetList::CLEAN_CODE,
        SetList::DEAD_CODE,
    ]);

    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/ecs.php',
        __DIR__ . '/rector.php',
        __DIR__ . '/config',
        __DIR__ . '/utils',
    ]);

    $services = $containerConfigurator->services();

    $services->set(LineLengthFixer::class);
    $services->set(YodaStyleFixer::class);

    $services->set(ReferenceUsedNamesOnlySniff::class)
        ->property('searchAnnotations', true)
        ->property('allowFullyQualifiedGlobalFunctions', true)
        ->property('allowFullyQualifiedGlobalConstants', true)
        ->property('allowPartialUses', false);
};
