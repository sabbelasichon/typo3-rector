<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/vendor/rector/rector-generator/config/config.php');
    $rectorConfig->phpstanConfig(__DIR__ . '/phpstan.neon');

    $rectorConfig->paths([__DIR__ . '/utils', __DIR__ . '/src', __DIR__ . '/tests', __DIR__ . '/rules']);

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
        __DIR__ . '/src/Set',
        // tests
        '*/Fixture/*',
        '*/Fixture*',
        '*/Source/*',
        '*/Source*',
        '*/Expected/*',
    ]);
};
