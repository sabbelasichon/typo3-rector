<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;

use Ssch\TYPO3Rector\Rector\v8\v4\ExtensionManagementUtilityExtRelPathRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    // @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.4/Deprecation-75363-DeprecateFormResultCompilerJStop.html
    $services->set(RenameMethodRector::class)
        ->configure([
            new MethodCallRename('TYPO3\CMS\Backend\Routing\FormResultCompiler', 'JStop', 'addCssFiles'),
        ]);
    // @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.4/Deprecation-77826-RTEHtmlAreaSpellcheckerEntrypoint.html
    $services->set(RenameMethodRector::class)
        ->configure([
            new MethodCallRename(
                'TYPO3\CMS\Saltedpasswords\Salt\SpellCheckingController',
                'main',
                'processRequest'
            ),
        ]);
    $services->set(ExtensionManagementUtilityExtRelPathRector::class);
};
