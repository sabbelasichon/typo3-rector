<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Transform\Rector\Assign\PropertyFetchToMethodCallRector;
use Rector\Transform\ValueObject\PropertyFetchToMethodCall;
use Ssch\TYPO3Rector\Rector\v10\v1\BackendUtilityEditOnClickRector;
use Ssch\TYPO3Rector\Rector\v10\v1\RefactorInternalPropertiesOfTSFERector;
use Ssch\TYPO3Rector\Rector\v10\v1\RegisterPluginWithVendorNameRector;
use Ssch\TYPO3Rector\Rector\v10\v1\SendNotifyEmailToMailApiRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
use TYPO3\CMS\Backend\History\RecordHistory;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');
    $services = $containerConfigurator->services();
    $services->set(RegisterPluginWithVendorNameRector::class);
    $services->set(BackendUtilityEditOnClickRector::class);
    $services->set('record_history_property_fetch_changelog_to_method_call_get_changelog')->class(
        PropertyFetchToMethodCallRector::class
    )
        ->call(
        'configure',
        [[
            PropertyFetchToMethodCallRector::PROPERTIES_TO_METHOD_CALLS => ValueObjectInliner::inline([
                new PropertyFetchToMethodCall(RecordHistory::class, 'changeLog', 'getChangeLog', 'setChangelog', [
                    'bla',
                ]), new PropertyFetchToMethodCall(
                    RecordHistory::class,
                    'lastHistoryEntry',
                    'getLastHistoryEntryNumber',
                    null,
                    []), ]
            ),
        ]]
    );
    $services->set('record_history_rename_methods')->class(RenameMethodRector::class)
        ->call('configure', [[
            RenameMethodRector::METHOD_CALL_RENAMES => ValueObjectInliner::inline([
                new MethodCallRename(RecordHistory::class, 'createChangeLog', 'getChangeLog'),
                new MethodCallRename(RecordHistory::class, 'getElementData', 'getElementInformation'),
                new MethodCallRename(RecordHistory::class, 'createMultipleDiff', 'getDiff'),
                new MethodCallRename(RecordHistory::class, 'setLastHistoryEntry', 'setLastHistoryEntryNumber'),
            ]),
        ]]);
    $services->set(SendNotifyEmailToMailApiRector::class);
    $services->set(RefactorInternalPropertiesOfTSFERector::class);
};
