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
use Ssch\TYPO3Rector\TypoScript\Conditions\ApplicationContextConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\BrowserConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\CompatVersionConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\GlobalStringConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\GlobalVarConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\HostnameConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\IPConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\LanguageConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\LoginUserConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\PageConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\PIDinRootlineConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\TimeConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\TreeLevelConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\UsergroupConditionMatcherMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\VersionConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Visitors\OldConditionToExpressionLanguageVisitor;
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

    $services->set(ApplicationContextConditionMatcher::class);
    $services->set(BrowserConditionMatcher::class);
    $services->set(CompatVersionConditionMatcher::class);
    $services->set(GlobalStringConditionMatcher::class);
    $services->set(GlobalVarConditionMatcher::class);
    $services->set(HostnameConditionMatcher::class);
    $services->set(IPConditionMatcher::class);
    $services->set(LanguageConditionMatcher::class);
    $services->set(LoginUserConditionMatcher::class);
    $services->set(PageConditionMatcher::class);
    $services->set(PIDinRootlineConditionMatcher::class);
    $services->set(TimeConditionMatcher::class);
    $services->set(TreeLevelConditionMatcher::class);
    $services->set(UsergroupConditionMatcherMatcher::class);
    $services->set(VersionConditionMatcher::class);
    $services->set(OldConditionToExpressionLanguageVisitor::class);
};
