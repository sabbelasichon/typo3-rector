<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Transform\Rector\MethodCall\MethodCallToStaticCallRector;
use Rector\Transform\ValueObject\MethodCallToStaticCall;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../../../config/config_test.php');
    $services = $containerConfigurator->services();

    $services->set(RenameMethodRector::class)
        ->configure([new MethodCallRename(DatabaseRecordList::class, 'requestUri', 'listURL')]);

    $services->set(MethodCallToStaticCallRector::class)
        ->configure([
            new MethodCallToStaticCall(DatabaseRecordList::class, 'thumbCode', BackendUtility::class, 'thumbCode'),
        ]);
};
