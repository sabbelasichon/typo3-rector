<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use function Rector\SymfonyPhpConfig\inline_value_objects;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Backend\Routing\FormResultCompiler;
use TYPO3\CMS\Saltedpasswords\Salt\SpellCheckingController;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');
    $services = $containerConfigurator->services();
    // @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.4/Deprecation-75363-DeprecateFormResultCompilerJStop.html
    $services->set(RenameMethodRector::class)->call('configure', [[
        RenameMethodRector::METHOD_CALL_RENAMES => inline_value_objects([
            new MethodCallRename(FormResultCompiler::class, 'JStop', 'addCssFiles'),
        ]),
    ]]);
    // @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.4/Deprecation-77826-RTEHtmlAreaSpellcheckerEntrypoint.html
    $services->set(RenameMethodRector::class)->call('configure', [[
        RenameMethodRector::METHOD_CALL_RENAMES => inline_value_objects([
            new MethodCallRename(SpellCheckingController::class, 'main', 'processRequest'),
        ]),
    ]]);
};
