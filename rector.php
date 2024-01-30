<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/config/config.php');

    $rectorConfig->parallel();
    $rectorConfig->importNames();
    $rectorConfig->importShortClasses(false);
    $rectorConfig->phpstanConfig(__DIR__ . '/phpstan.neon');

    $rectorConfig->paths([__DIR__ . '/utils', __DIR__ . '/src', __DIR__ . '/tests']);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_74,
        SetList::PRIVATIZATION,
        SetList::CODING_STYLE,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
    ]);

    $rectorConfig->skip([
        RemoveUnusedVariableAssignRector::class,
        __DIR__ . '/utils/generator/templates',
        StringClassNameToClassConstantRector::class,
        __DIR__ . '/src/Rector/v8/v0/RefactorRemovedMethodsFromContentObjectRendererRector.php',
        __DIR__ . '/src/Rector/v8/v6/RefactorTCARector.php',
        __DIR__ . '/src/Set',
        // tests
        '*/Fixture/*',
        '*/Fixture*',
        '*/Source/*',
        '*/Source*',
        '*/Expected/*',
    ]);

    $rectorConfig->rule(ClassPropertyAssignToConstructorPromotionRector::class);
};
