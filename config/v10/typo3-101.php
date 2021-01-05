<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Transform\Rector\Assign\PropertyToMethodRector;
use Rector\Transform\ValueObject\PropertyToMethod;
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
    $services->set(PropertyToMethodRector::class)->call('configure', [[
        PropertyToMethodRector::PROPERTIES_TO_METHOD_CALLS => ValueObjectInliner::inline([
            new PropertyToMethod(RecordHistory::class, 'changeLog', 'getChangeLog', 'setChangelog', [
                'bla',
            ]), new PropertyToMethod(
                RecordHistory::class,
                'lastHistoryEntry',
                'getLastHistoryEntryNumber',
                null,
                []), ]
        ),
    ]]);
    $services->set(RenameMethodRector::class)->call('configure', [[
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
