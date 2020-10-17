<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use function Rector\SymfonyPhpConfig\inline_value_objects;
use Ssch\TYPO3Rector\Rector\Core\Html\RteHtmlParserRector;
use Ssch\TYPO3Rector\Rector\Core\TimeTracker\TimeTrackerGlobalsToSingletonRector;
use Ssch\TYPO3Rector\Rector\Core\Utility\RefactorRemovedMethodsFromGeneralUtilityRector;
use Ssch\TYPO3Rector\Rector\Extbase\RemovePropertyUserAuthenticationRector;
use Ssch\TYPO3Rector\Rector\Fluid\View\ChangeMethodCallsForStandaloneViewRector;
use Ssch\TYPO3Rector\Rector\Frontend\ContentObject\RefactorRemovedMethodsFromContentObjectRendererRector;
use Ssch\TYPO3Rector\Rector\v8\v0\RemoveWakeupCallFromEntityRector;
use Ssch\TYPO3Rector\Rector\v8\v0\RefactorRemovedMarkerMethodsFromHtmlParserRector;
use Ssch\TYPO3Rector\Rector\v8\v0\RemoveRteHtmlParserEvalWriteFileRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');

    $services = $containerConfigurator->services();

    $services->set(ChangeMethodCallsForStandaloneViewRector::class);

    $services->set(RefactorRemovedMethodsFromGeneralUtilityRector::class);

    $services->set(RefactorRemovedMethodsFromContentObjectRendererRector::class);

    $services->set(RemovePropertyUserAuthenticationRector::class);

    $services->set(TimeTrackerGlobalsToSingletonRector::class);

    $services->set(RemoveWakeupCallFromEntityRector::class);

    $services->set(RteHtmlParserRector::class);

    $services->set(RenameMethodRector::class)
        ->call('configure', [
            [
                RenameMethodRector::METHOD_CALL_RENAMES => inline_value_objects([
                    new MethodCallRename('TYPO3\CMS\Recordlist\RecordList', 'printContent', 'mainAction'),
                    new MethodCallRename(
                        'TYPO3\CMS\Recordlist\Controller\ElementBrowserFramesetController',
                        'printContent',
                        'mainAction'
                    ),
                    new MethodCallRename('TYPO3\CMS\Rtehtmlarea\Controller\UserElementsController', 'main',
                        'main_user'),
                    new MethodCallRename(
                        'TYPO3\CMS\Rtehtmlarea\Controller\UserElementsController',
                        'printContent',
                        'mainAction'
                    ),
                    new MethodCallRename('TYPO3\CMS\Rtehtmlarea\Controller\ParseHtmlController', 'main',
                        'main_parse_html'),
                    new MethodCallRename(
                        'TYPO3\CMS\Rtehtmlarea\Controller\ParseHtmlController',
                        'printContent',
                        'mainAction'
                    ),
                ]),
            ],
        ]);
    $services->set(RenameStaticMethodRector::class)
        ->call('configure', [
            [
                RenameStaticMethodRector::OLD_TO_NEW_METHODS_BY_CLASSES => inline_value_objects(
                    [
                        new RenameStaticMethod(ExtensionUtility::class, 'configureModule',
                            ExtensionManagementUtility::class, 'configureModule'),
                    ]
                ),
            ],
        ]);

    $services->set(RefactorRemovedMarkerMethodsFromHtmlParserRector::class);
    $services->set(RemoveRteHtmlParserEvalWriteFileRector::class);
};
