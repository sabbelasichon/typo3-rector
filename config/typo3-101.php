<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Transform\Rector\Assign\PropertyToMethodRector;
use Rector\Transform\ValueObject\PropertyToMethod;
use Ssch\TYPO3Rector\Rector\Backend\Utility\BackendUtilityEditOnClickRector;
use Ssch\TYPO3Rector\Rector\Extbase\RegisterPluginWithVendorNameRector;
use Ssch\TYPO3Rector\Rector\v10\v1\SubstituteDeprecatedRecordHistoryMethodsRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Rector\SymfonyPhpConfig\inline_value_objects;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');

    $services = $containerConfigurator->services();

    $services->set(RegisterPluginWithVendorNameRector::class);

    $services->set(BackendUtilityEditOnClickRector::class);

    $services->set(PropertyToMethodRector::class)
        ->call('configure', [[
            PropertyToMethodRector::PROPERTIES_TO_METHOD_CALLS => inline_value_objects([
                new PropertyToMethod( \TYPO3\CMS\Backend\History\RecordHistory::class, 'changeLog', 'getChangeLog','setChangelog',['bla']),
                new PropertyToMethod( \TYPO3\CMS\Backend\History\RecordHistory::class, 'lastHistoryEntry', 'getLastHistoryEntryNumber',null,[]),
            ]),
        ]]);

    $services->set(RenameMethodRector::class)
        ->call('configure', [
            [
                RenameMethodRector::METHOD_CALL_RENAMES => inline_value_objects([
                    new MethodCallRename(
                        \TYPO3\CMS\Backend\History\RecordHistory::class,
                        'createChangeLog',
                        'getChangeLog'
                    ),
                    new MethodCallRename(
                        \TYPO3\CMS\Backend\History\RecordHistory::class,
                        'getElementData',
                        'getElementInformation'
                    ),
                    new MethodCallRename(
                        \TYPO3\CMS\Backend\History\RecordHistory::class,
                        'createMultipleDiff',
                        'getDiff'
                    ),
                    new MethodCallRename(
                        \TYPO3\CMS\Backend\History\RecordHistory::class,
                        'setLastHistoryEntry',
                        'setLastHistoryEntryNumber'
                    ),

                ]),
            ],
        ]);

    $services->set(SubstituteDeprecatedRecordHistoryMethodsRector::class);
};
