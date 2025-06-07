<?php

declare(strict_types=1);

use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\AssignmentInConditionSniff;
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Operator\OperatorLinebreakFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayOpenerAndCloserNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\StandaloneLineInMultilineArrayFixer;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/rules',
        __DIR__ . '/ecs.php',
        __DIR__ . '/rector.php',
        __DIR__ . '/bin',
        __DIR__ . '/config',
        __DIR__ . '/utils',
    ]);

    $ecsConfig->skip([
        // on php 8.1, it adds space on &$variable
        __DIR__ . '/utils/generator/templates/rules',
        __DIR__ . '/tests/Rector/v12/v0/MigrateBackendModuleRegistrationRector/Assertions/beuser/Configuration/Backend/Modules.php',
        __DIR__ . '/tests/Rector/v12/v0/MigrateBackendModuleRegistrationRector/Assertions/extbase/Configuration/Backend/Modules.php',
        __DIR__ . '/tests/Rector/v12/v0/MigrateBackendModuleRegistrationRector/Assertions/extension_empty_access/Configuration/Backend/Modules.php',
        __DIR__ . '/tests/Rector/v12/v0/MigrateBackendModuleRegistrationRector/Assertions/install/Configuration/Backend/Modules.php',
        AssignmentInConditionSniff::class,
        DeclareStrictTypesFixer::class => ['*/Fixture/*', '*/Assertions/*'],
        LineLengthFixer::class => [__DIR__ . '/config/v13/strict-types.php'],
    ]);

    $ecsConfig->sets([SetList::PSR_12, SetList::SYMPLIFY, SetList::COMMON, SetList::CLEAN_CODE]);

    $ecsConfig->ruleWithConfiguration(GeneralPhpdocAnnotationRemoveFixer::class, [
        'annotations' => ['throws', 'author', 'package', 'group'],
    ]);

    $ecsConfig->ruleWithConfiguration(NoSuperfluousPhpdocTagsFixer::class, [
        'allow_mixed' => true,
    ]);

    $ecsConfig->rule(NoUnusedImportsFixer::class);
    $ecsConfig->rule(ArraySyntaxFixer::class);
    $ecsConfig->rule(StandaloneLineInMultilineArrayFixer::class);
    $ecsConfig->rule(ArrayOpenerAndCloserNewlineFixer::class);
    $ecsConfig->rule(DeclareStrictTypesFixer::class);
    $ecsConfig->rule(LineLengthFixer::class);
    $ecsConfig->rule(YodaStyleFixer::class);
    $ecsConfig->rule(OperatorLinebreakFixer::class);
};
