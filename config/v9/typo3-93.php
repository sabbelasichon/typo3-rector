<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Ssch\TYPO3Rector\Rector\v9\v3\BackendUserAuthenticationSimplelogRector;
use Ssch\TYPO3Rector\Rector\v9\v3\BackendUtilityGetModuleUrlRector;
use Ssch\TYPO3Rector\Rector\v9\v3\CopyMethodGetPidForModTSconfigRector;
use Ssch\TYPO3Rector\Rector\v9\v3\MoveLanguageFilesFromExtensionLangRector;
use Ssch\TYPO3Rector\Rector\v9\v3\PropertyUserTsToMethodGetTsConfigOfBackendUserAuthenticationRector;
use Ssch\TYPO3Rector\Rector\v9\v3\RefactorTsConfigRelatedMethodsRector;
use Ssch\TYPO3Rector\Rector\v9\v3\RemoveColPosParameterRector;
use Ssch\TYPO3Rector\Rector\v9\v3\UseMethodGetPageShortcutDirectlyFromSysPageRector;
use Ssch\TYPO3Rector\Rector\v9\v3\ValidateAnnotationRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
use TYPO3\CMS\Backend\Controller\Page\LocalizationController;
use TYPO3\CMS\Extbase\Mvc\Controller\Argument;
use TYPO3\CMS\Extbase\Mvc\Controller\Arguments;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');
    $services = $containerConfigurator->services();
    $services->set(RemoveColPosParameterRector::class);
    $services->set(ValidateAnnotationRector::class);
    $services->set(
        'localization_controller_get_used_languages_in_page_and_column_to_get_used_languages_in_page'
    )->class(RenameMethodRector::class)
        ->call(
        'configure',
        [[
            RenameMethodRector::METHOD_CALL_RENAMES => ValueObjectInliner::inline([
                new MethodCallRename(
                    LocalizationController::class,
                    'getUsedLanguagesInPageAndColumn',
                    'getUsedLanguagesInPage'
                ),
            ]),
        ]]
    );
    $services->set(BackendUtilityGetModuleUrlRector::class);
    $services->set(PropertyUserTsToMethodGetTsConfigOfBackendUserAuthenticationRector::class);
    $services->set(UseMethodGetPageShortcutDirectlyFromSysPageRector::class);
    $services->set(CopyMethodGetPidForModTSconfigRector::class);
    $services->set(BackendUserAuthenticationSimplelogRector::class);
    $services->set(MoveLanguageFilesFromExtensionLangRector::class);
    $services->set('get_validation_results_to_validate')
        ->class(RenameMethodRector::class)
        ->call('configure', [[
            RenameMethodRector::METHOD_CALL_RENAMES => ValueObjectInliner::inline([
                new MethodCallRename(Argument::class, 'getValidationResults', 'validate'),
                new MethodCallRename(Arguments::class, 'getValidationResults', 'validate'),
            ]),
        ]]);
    $services->set(RefactorTsConfigRelatedMethodsRector::class);
};
