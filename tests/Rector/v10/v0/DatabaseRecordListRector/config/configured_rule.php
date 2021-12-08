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

<<<<<<< HEAD
<<<<<<< HEAD
    $services->set('rename_database_record_list_request_uri_to_list_url')
        ->class(RenameMethodRector::class)
=======
    $services->set(RenameMethodRector::class)
>>>>>>> c7f0e20d... remove string-class names, not needed
        ->call('configure', [[
            RenameMethodRector::METHOD_CALL_RENAMES => ValueObjectInliner::inline([
                new MethodCallRename(DatabaseRecordList::class, 'requestUri', 'listURL'),
            ]),
        ]]);
=======
    $services->set(RenameMethodRector::class)
<<<<<<< HEAD
        ->configure([
            new MethodCallRename(DatabaseRecordList::class, 'requestUri', 'listURL'),
        ]);
>>>>>>> 9786beb5... fixup! make use of configure() method
=======
        ->configure([new MethodCallRename(DatabaseRecordList::class, 'requestUri', 'listURL')]);
>>>>>>> a6246211... fixup! fixup! make use of configure() method

    $services->set(MethodCallToStaticCallRector::class)
        ->configure([
            new MethodCallToStaticCall(
                DatabaseRecordList::class,
                'thumbCode',
                BackendUtility::class,
                'thumbCode'
            )
        ]);
};
