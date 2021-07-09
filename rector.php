<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassConst\VarConstantCommentRector;
use Rector\CodingStyle\Rector\String_\SplitStringClassConstantToClassConstFetchRector;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector;
use Rector\Nette\Set\NetteSetList;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Php74\Rector\MethodCall\ChangeReflectionTypeToStringToGetNameRector;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\PHPUnit\Rector\Class_\AddSeeTestAnnotationRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\SetList;
use Ssch\TYPO3Rector\ComposerPackages\Rector\RemovePackageVersionsRector;
use Ssch\TYPO3Rector\Rules\Rector\Misc\AddCodeCoverageIgnoreToMethodRectorDefinitionRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/config/config.php');

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::PHPSTAN_FOR_RECTOR_PATH, __DIR__ . '/phpstan.neon');

    $services = $containerConfigurator->services();

    $services->set(AddCodeCoverageIgnoreToMethodRectorDefinitionRector::class);
    $services->set(AddSeeTestAnnotationRector::class);
    $services->set(VarConstantCommentRector::class);

    $parameters->set(Option::PATHS, [
        __DIR__ . '/utils',
        __DIR__ . '/src',
        __DIR__ . '/tests'
    ]);

    $containerConfigurator->import(SetList::PRIVATIZATION);
    $containerConfigurator->import(SetList::CODING_STYLE);
    $containerConfigurator->import(SetList::CODE_QUALITY);
    $containerConfigurator->import(SetList::EARLY_RETURN);
    $containerConfigurator->import(SetList::TYPE_DECLARATION);
    $containerConfigurator->import(SetList::TYPE_DECLARATION_STRICT);
    $containerConfigurator->import(NetteSetList::NETTE_UTILS_CODE_QUALITY);
    $containerConfigurator->import(PHPUnitSetList::PHPUNIT_CODE_QUALITY);
    $containerConfigurator->import(SetList::PHP_71);
    $containerConfigurator->import(SetList::PHP_72);
    $containerConfigurator->import(SetList::PHP_73);
    $containerConfigurator->import(SetList::PHP_74);
    $containerConfigurator->import(SetList::PHP_80);

    $parameters->set(
        Option::SKIP,
        [
            // buggy in check ReflectionNamedType
            ChangeReflectionTypeToStringToGetNameRector::class,
            RemoveUnusedVariableAssignRector::class,
            // some classes in config might not exist without dev dependencies
            SplitStringClassConstantToClassConstFetchRector::class,
            __DIR__ . '/utils/generator/templates',
            StringClassNameToClassConstantRector::class,
            __DIR__ . '/src/Rector/v8/v6/RefactorTCARector.php',
            RemovePackageVersionsRector::class => [
                __DIR__ . '/config',
                __DIR__ . '/tests'
            ],
            __DIR__ . '/src/Set',
            // test paths
            '*/Fixture/*',
            '*/Fixture*/*',
            '*/Source/*',
            '*/Source*/*',
            '*/Expected/*',
            '*/Expected*/*',
        ]
    );

    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_80);
};
