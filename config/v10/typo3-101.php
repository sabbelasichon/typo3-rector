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

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set(RegisterPluginWithVendorNameRector::class);
    $services->set(BackendUtilityEditOnClickRector::class);
    $services->set(PropertyFetchToMethodCallRector::class)
        ->configure([
            new PropertyFetchToMethodCall(
                'TYPO3\CMS\Backend\History\RecordHistory',
                'changeLog',
                'getChangeLog',
                'setChangelog',
                ['bla']
            ),
            new PropertyFetchToMethodCall(
                'TYPO3\CMS\Backend\History\RecordHistory',
                'lastHistoryEntry',
                'getLastHistoryEntryNumber',
                null,
                []
            ),
        ]);
    $services->set(RenameMethodRector::class)
        ->configure([
            new MethodCallRename('TYPO3\CMS\Backend\History\RecordHistory', 'createChangeLog', 'getChangeLog'),
            new MethodCallRename(
                'TYPO3\CMS\Backend\History\RecordHistory',
                'getElementData',
                'getElementInformation'
            ),
            new MethodCallRename('TYPO3\CMS\Backend\History\RecordHistory', 'createMultipleDiff', 'getDiff'),
            new MethodCallRename(
                'TYPO3\CMS\Backend\History\RecordHistory',
                'setLastHistoryEntry',
                'setLastHistoryEntryNumber'
            ),
        ]);
    $services->set(SendNotifyEmailToMailApiRector::class);
    $services->set(RefactorInternalPropertiesOfTSFERector::class);
    $services->set(\Ssch\TYPO3Rector\Rector\v10\v1\RemoveEnableMultiSelectFilterTextfieldRector::class);
};
