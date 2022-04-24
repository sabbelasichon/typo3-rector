<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassConst\VarConstantCommentRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\PHPUnit\Rector\Class_\AddSeeTestAnnotationRector;
use Rector\Set\ValueObject\SetList;
use Ssch\TYPO3Rector\ComposerPackages\Rector\RemovePackageVersionsRector;
use Ssch\TYPO3Rector\Rules\Rector\Misc\AddCodeCoverageIgnoreToMethodRectorDefinitionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/config/config.php');

    $rectorConfig->parallel();
    $rectorConfig->importNames();
    $rectorConfig->phpstanConfig(__DIR__ . '/phpstan.neon');

    $rectorConfig->rule(AddCodeCoverageIgnoreToMethodRectorDefinitionRector::class);
    $rectorConfig->rule(AddSeeTestAnnotationRector::class);
    $rectorConfig->rule(VarConstantCommentRector::class);

    $rectorConfig->paths([__DIR__ . '/utils', __DIR__ . '/src', __DIR__ . '/tests']);

    $rectorConfig->sets([
        \Rector\Set\ValueObject\LevelSetList::UP_TO_PHP_80,
        SetList::PRIVATIZATION,
        SetList::CODING_STYLE,
        SetList::CODE_QUALITY,
        SetList::NAMING,
    ]);

    $rectorConfig->skip([
        RemoveUnusedVariableAssignRector::class,
        __DIR__ . '/utils/generator/templates',
        StringClassNameToClassConstantRector::class,
        __DIR__ . '/src/Rector/v8/v0/RefactorRemovedMethodsFromContentObjectRendererRector.php',
        __DIR__ . '/src/Rector/v8/v6/RefactorTCARector.php',
        RemovePackageVersionsRector::class => [__DIR__ . '/config', __DIR__ . '/tests'],
        __DIR__ . '/src/Set',
        '*/Fixture/*',
    ]);

    $rectorConfig->phpVersion(PhpVersion::PHP_81);

    $rectorConfig->rule(TypedPropertyRector::class);
    $rectorConfig->rule(ClassPropertyAssignToConstructorPromotionRector::class);
    $rectorConfig->rule(\Rector\Php81\Rector\Property\ReadOnlyPropertyRector::class);
};
