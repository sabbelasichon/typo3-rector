<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Transform\Rector\MethodCall\MethodCallToStaticCallRector;
use Rector\Transform\ValueObject\MethodCallToStaticCall;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config.php');

    $rectorConfig
        ->ruleWithConfiguration(
            RenameMethodRector::class,
            [new MethodCallRename(DatabaseRecordList::class, 'requestUri', 'listURL')]
        );

    $rectorConfig
        ->ruleWithConfiguration(MethodCallToStaticCallRector::class, [
            new MethodCallToStaticCall(DatabaseRecordList::class, 'thumbCode', BackendUtility::class, 'thumbCode'),
        ]);
};
