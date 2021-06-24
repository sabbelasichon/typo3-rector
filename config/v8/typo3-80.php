<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Visitors\AdditionalHeadersToArrayVisitor;
use Ssch\TYPO3Rector\Rector\v8\v0\ChangeMethodCallsForStandaloneViewRector;
use Ssch\TYPO3Rector\Rector\v8\v0\GetFileAbsFileNameRemoveDeprecatedArgumentsRector;
use Ssch\TYPO3Rector\Rector\v8\v0\GetPreferredClientLanguageRector;
use Ssch\TYPO3Rector\Rector\v8\v0\PrependAbsolutePathToGetFileAbsFileNameRector;
use Ssch\TYPO3Rector\Rector\v8\v0\RandomMethodsToRandomClassRector;
use Ssch\TYPO3Rector\Rector\v8\v0\RefactorRemovedMarkerMethodsFromHtmlParserRector;
use Ssch\TYPO3Rector\Rector\v8\v0\RefactorRemovedMethodsFromContentObjectRendererRector;
use Ssch\TYPO3Rector\Rector\v8\v0\RefactorRemovedMethodsFromGeneralUtilityRector;
use Ssch\TYPO3Rector\Rector\v8\v0\RemoveCharsetConverterParametersRector;
use Ssch\TYPO3Rector\Rector\v8\v0\RemoveLangCsConvObjAndParserFactoryRector;
use Ssch\TYPO3Rector\Rector\v8\v0\RemovePropertyUserAuthenticationRector;
use Ssch\TYPO3Rector\Rector\v8\v0\RemoveRteHtmlParserEvalWriteFileRector;
use Ssch\TYPO3Rector\Rector\v8\v0\RemoveWakeupCallFromEntityRector;
use Ssch\TYPO3Rector\Rector\v8\v0\RenderCharsetDefaultsToUtf8Rector;
use Ssch\TYPO3Rector\Rector\v8\v0\RequireMethodsToNativeFunctionsRector;
use Ssch\TYPO3Rector\Rector\v8\v0\RteHtmlParserRector;
use Ssch\TYPO3Rector\Rector\v8\v0\TimeTrackerGlobalsToSingletonRector;
use Ssch\TYPO3Rector\Rector\v8\v0\TimeTrackerInsteadOfNullTimeTrackerRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set(ChangeMethodCallsForStandaloneViewRector::class);
    $services->set(RefactorRemovedMethodsFromGeneralUtilityRector::class);
    $services->set(RefactorRemovedMethodsFromContentObjectRendererRector::class);
    $services->set(RemovePropertyUserAuthenticationRector::class);
    $services->set(TimeTrackerGlobalsToSingletonRector::class);
    $services->set(RemoveWakeupCallFromEntityRector::class);
    $services->set(RteHtmlParserRector::class);
    $services->set('rename_method_print_action_to_main_action')
        ->class(RenameMethodRector::class)
        ->call(
            'configure',
            [[
                RenameMethodRector::METHOD_CALL_RENAMES => ValueObjectInliner::inline([

                    new MethodCallRename('TYPO3\CMS\Recordlist\RecordList', 'printContent', 'mainAction'),
                    new MethodCallRename(
                        'TYPO3\CMS\Recordlist\Controller\ElementBrowserFramesetController',
                        'printContent',
                        'mainAction'
                    ),
                    new MethodCallRename(
                        'TYPO3\CMS\Rtehtmlarea\Controller\UserElementsController',
                        'main',
                        'main_user'
                    ),
                    new MethodCallRename(
                        'TYPO3\CMS\Rtehtmlarea\Controller\UserElementsController',
                        'printContent',
                        'mainAction'
                    ),
                    new MethodCallRename(
                        'TYPO3\CMS\Rtehtmlarea\Controller\ParseHtmlController',
                        'main',
                        'main_parse_html'
                    ),
                    new MethodCallRename(
                        'TYPO3\CMS\Rtehtmlarea\Controller\ParseHtmlController',
                        'printContent',
                        'mainAction'
                    ),

                ]),
            ]]
        );
    $services->set('rename_static_methods_version_80')
        ->class(RenameStaticMethodRector::class)
        ->call(
            'configure',
            [[
                RenameStaticMethodRector::OLD_TO_NEW_METHODS_BY_CLASSES => ValueObjectInliner::inline([
                    new RenameStaticMethod(
                        'TYPO3\CMS\Extbase\Utility\ExtensionUtility',
                        'configureModule',
                        'TYPO3\CMS\Core\Utility\ExtensionManagementUtility',
                        'configureModule'
                    ),
                    new RenameStaticMethod(
                        'TYPO3\CMS\Core\TypoScript\TemplateService',
                        'sortedKeyList',
                        'TYPO3\CMS\Core\Utility\ArrayUtility',
                        'filterAndSortByNumericKeys'
                    ),
                    new RenameStaticMethod(
                        'TYPO3\CMS\Core\Utility\GeneralUtility',
                        'imageMagickCommand',
                        'TYPO3\CMS\Core\Utility\CommandUtility',
                        'imageMagickCommand'
                    ),

                ]),
            ]]
        );
    $services->set(PrependAbsolutePathToGetFileAbsFileNameRector::class);
    $services->set(RefactorRemovedMarkerMethodsFromHtmlParserRector::class);
    $services->set(RemoveRteHtmlParserEvalWriteFileRector::class);
    $services->set(RandomMethodsToRandomClassRector::class);
    $services->set(RequireMethodsToNativeFunctionsRector::class);
    $services->set(GetPreferredClientLanguageRector::class);
    $services->set('rename_method_get_template_variable_container_to_get_variable_provider')
        ->class(RenameMethodRector::class)
        ->call(
            'configure',
            [[
                RenameMethodRector::METHOD_CALL_RENAMES => ValueObjectInliner::inline([

                    new MethodCallRename(
                        'TYPO3\CMS\Fluid\Core\Rendering\RenderingContext',
                        'getTemplateVariableContainer',
                        'getVariableProvider'
                    ),

                ]),
            ]]
        );
    $services->set(TimeTrackerInsteadOfNullTimeTrackerRector::class);
    $services->set(RemoveCharsetConverterParametersRector::class);
    $services->set(GetFileAbsFileNameRemoveDeprecatedArgumentsRector::class);
    $services->set(RemoveLangCsConvObjAndParserFactoryRector::class);
    $services->set(RenderCharsetDefaultsToUtf8Rector::class);
    $services->set(AdditionalHeadersToArrayVisitor::class);
};
