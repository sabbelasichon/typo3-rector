<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\String_\UseClassKeywordForClassNameResolutionRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\PostRector\Rector\UnusedImportRemovingPostRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/rules',
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/bin',
        __DIR__ . '/utils',
    ])
    ->withPHPStanConfigs([__DIR__ . '/phpstan.neon'])
    ->withImportNames(true, true, false, true)
    ->withSkip([
        RemoveUnusedVariableAssignRector::class,
        __DIR__ . '/utils/generator/templates',
        StringClassNameToClassConstantRector::class,
        __DIR__ . '/src/Set',
        UseClassKeywordForClassNameResolutionRector::class => [
            __DIR__ . '/rules/TYPO311/v0/ForwardResponseInsteadOfForwardMethodRector.php', // Don't import TYPO3 namespace
            __DIR__ . '/rules/TYPO310/v0/UseNativePhpHex2binMethodRector.php', // Don't import TYPO3 namespace
            __DIR__ . '/rules/TYPO312/v0/MigrateFetchAllToFetchAllAssociativeRector.php', // Don't replace Doctrine Constants
            __DIR__ . '/rules/TYPO312/v0/MigrateFetchToFetchAssociativeRector.php', // Don't replace Doctrine Constants
        ],
        UnusedImportRemovingPostRector::class => [
            __DIR__ . '/tests/Rector/v13/v4/MigratePluginContentElementAndPluginSubtypesRector/Assertions/extension1/Classes/Updates/TYPO3RectorCTypeMigration.php', // Don't remove PHP8 Attribute
            __DIR__ . '/tests/Rector/v13/v4/MigratePluginContentElementAndPluginSubtypesRector/Assertions/extension2/Classes/Updates/TYPO3RectorCTypeMigration.php', // Don't remove PHP8 Attribute
        ],
        // tests
        '*/Assertions/*',
        '*/Fixture/*',
        '*/Fixture*',
        '*/Source/*',
        '*/Source*',
        '*/Expected/*',
        __DIR__ . '/tests/Rector/CodeQuality/General/',
    ])
    ->withSets([
        LevelSetList::UP_TO_PHP_74,
        SetList::PRIVATIZATION,
        SetList::CODING_STYLE,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
    ]);
