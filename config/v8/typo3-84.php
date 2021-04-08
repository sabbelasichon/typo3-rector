<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;

use Ssch\TYPO3Rector\Rector\v8\v4\ExtensionManagementUtilityExtRelPathRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
use TYPO3\CMS\Backend\Routing\FormResultCompiler;
use TYPO3\CMS\Saltedpasswords\Salt\SpellCheckingController;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');
    $services = $containerConfigurator->services();
    // @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.4/Deprecation-75363-DeprecateFormResultCompilerJStop.html
    $services->set('form_result_compiler_jstop_to_add_css_files')
        ->class(RenameMethodRector::class)
        ->call(
        'configure',
        [[
            RenameMethodRector::METHOD_CALL_RENAMES => ValueObjectInliner::inline([
                new MethodCallRename(FormResultCompiler::class, 'JStop', 'addCssFiles'),
            ]),
        ]]
    );
    // @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.4/Deprecation-77826-RTEHtmlAreaSpellcheckerEntrypoint.html
    $services->set('spell_checking_controller_main_to_process_request')
        ->class(RenameMethodRector::class)
        ->call(
        'configure',
        [[
            RenameMethodRector::METHOD_CALL_RENAMES => ValueObjectInliner::inline([
                new MethodCallRename(SpellCheckingController::class, 'main', 'processRequest'),
            ]),
        ]]
    );
    $services->set(ExtensionManagementUtilityExtRelPathRector::class);
};
