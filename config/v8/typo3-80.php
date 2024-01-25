<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\v8\v0\DefaultSwitchFluidRector;
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

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(ChangeMethodCallsForStandaloneViewRector::class);
    $rectorConfig->rule(RefactorRemovedMethodsFromGeneralUtilityRector::class);
    $rectorConfig->rule(RefactorRemovedMethodsFromContentObjectRendererRector::class);
    $rectorConfig->rule(RemovePropertyUserAuthenticationRector::class);
    $rectorConfig->rule(TimeTrackerGlobalsToSingletonRector::class);
    $rectorConfig->rule(RemoveWakeupCallFromEntityRector::class);
    $rectorConfig->rule(RteHtmlParserRector::class);
    $rectorConfig
        ->ruleWithConfiguration(RenameMethodRector::class, [
            new MethodCallRename('TYPO3\CMS\Recordlist\RecordList', 'printContent', 'mainAction'),
            new MethodCallRename(
                'TYPO3\CMS\Recordlist\Controller\ElementBrowserFramesetController',
                'printContent',
                'mainAction'
            ),
            new MethodCallRename('TYPO3\CMS\Rtehtmlarea\Controller\UserElementsController', 'main', 'main_user'),
            new MethodCallRename(
                'TYPO3\CMS\Rtehtmlarea\Controller\UserElementsController',
                'printContent',
                'mainAction'
            ),
            new MethodCallRename('TYPO3\CMS\Rtehtmlarea\Controller\ParseHtmlController', 'main', 'main_parse_html'),
            new MethodCallRename(
                'TYPO3\CMS\Rtehtmlarea\Controller\ParseHtmlController',
                'printContent',
                'mainAction'
            ),
        ]);
    $rectorConfig
        ->ruleWithConfiguration(RenameStaticMethodRector::class, [
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
        ]);
    $rectorConfig->rule(PrependAbsolutePathToGetFileAbsFileNameRector::class);
    $rectorConfig->rule(RefactorRemovedMarkerMethodsFromHtmlParserRector::class);
    $rectorConfig->rule(RemoveRteHtmlParserEvalWriteFileRector::class);
    $rectorConfig->rule(RandomMethodsToRandomClassRector::class);
    $rectorConfig->rule(RequireMethodsToNativeFunctionsRector::class);
    $rectorConfig->rule(GetPreferredClientLanguageRector::class);
    $rectorConfig
        ->ruleWithConfiguration(RenameMethodRector::class, [
            new MethodCallRename(
                'TYPO3\CMS\Fluid\Core\Rendering\RenderingContext',
                'getTemplateVariableContainer',
                'getVariableProvider'
            ),
        ]);
    $rectorConfig->rule(TimeTrackerInsteadOfNullTimeTrackerRector::class);
    $rectorConfig->rule(RemoveCharsetConverterParametersRector::class);
    $rectorConfig->rule(GetFileAbsFileNameRemoveDeprecatedArgumentsRector::class);
    $rectorConfig->rule(RemoveLangCsConvObjAndParserFactoryRector::class);
    $rectorConfig->rule(RenderCharsetDefaultsToUtf8Rector::class);
    $rectorConfig->services()
        ->set(DefaultSwitchFluidRector::class)->tag('typo3_rector.fluid_rectors');
};
