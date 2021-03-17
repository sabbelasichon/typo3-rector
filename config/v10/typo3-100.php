<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Transform\Rector\MethodCall\MethodCallToStaticCallRector;
use Rector\Transform\ValueObject\MethodCallToStaticCall;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList;

return static function (
    \Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator
): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');
    $services = $containerConfigurator->services();
    $services->set(\Ssch\TYPO3Rector\Rector\v10\v0\RemovePropertyExtensionNameRector::class);
    $services->set(\Ssch\TYPO3Rector\Rector\v10\v0\UseNativePhpHex2binMethodRector::class);
    $services->set(\Ssch\TYPO3Rector\Rector\v10\v0\RefactorIdnaEncodeMethodToNativeFunctionRector::class);
    $services->set('rename_namespace_backend_controller_file_to_filelist_controller_file')
        ->class(\Rector\Renaming\Rector\Namespace_\RenameNamespaceRector::class)->call(
        'configure',
        [[
            \Rector\Renaming\Rector\Namespace_\RenameNamespaceRector::OLD_TO_NEW_NAMESPACES => [
                'TYPO3\CMS\Backend\Controller\File' => 'TYPO3\CMS\Filelist\Controller\File',

            ],
        ]]
    );
    $services->set(\Ssch\TYPO3Rector\Rector\v10\v0\UseMetaDataAspectRector::class);
    $services->set(\Ssch\TYPO3Rector\Rector\v10\v0\ForceTemplateParsingInTsfeAndTemplateServiceRector::class);
    $services->set(\Ssch\TYPO3Rector\Rector\v10\v0\BackendUtilityGetViewDomainToPageRouterRector::class);
    $services->set(\Ssch\TYPO3Rector\Rector\v10\v0\SetSystemLocaleFromSiteLanguageRector::class);
    $services->set(\Ssch\TYPO3Rector\Rector\v10\v0\ConfigurationManagerAddControllerConfigurationMethodRector::class);
    $services->set(\Ssch\TYPO3Rector\Rector\v10\v0\RemoveFormatConstantsEmailFinisherRector::class);
    $services->set(\Ssch\TYPO3Rector\Rector\v10\v0\UseTwoLetterIsoCodeFromSiteLanguageRector::class);
    $services->set(\Ssch\TYPO3Rector\Rector\v10\v0\UseControllerClassesInExtbasePluginsAndModulesRector::class);
    $services->set(\Ssch\TYPO3Rector\Rector\v10\v0\ChangeDefaultCachingFrameworkNamesRector::class);
    $services->set(\Ssch\TYPO3Rector\Rector\General\ExtEmConfRector::class)->call('configure', [[
        \Ssch\TYPO3Rector\Rector\General\ExtEmConfRector::ADDITIONAL_VALUES_TO_BE_REMOVED => [
            'createDirs',
            'uploadfolder',
        ],
    ]]);
    $services->set(\Ssch\TYPO3Rector\Rector\v10\v0\SwiftMailerBasedMailMessageToMailerBasedMessageRector::class);

    $services->set('rename_database_record_list_thumb_code_backend_utility_thumb_code')
        ->class(MethodCallToStaticCallRector::class)
        ->call('configure', [[
            MethodCallToStaticCallRector::METHOD_CALLS_TO_STATIC_CALLS => ValueObjectInliner::inline([
                new MethodCallToStaticCall(
                   DatabaseRecordList::class,
                   'thumbCode',
                   BackendUtility::class,
                   'thumbCode'
                    ),
            ]),
        ]]);

    $services->set('rename_database_record_list_request_uri_to_list_url')
        ->class(RenameMethodRector::class)
        ->call('configure', [[
            RenameMethodRector::METHOD_CALL_RENAMES => ValueObjectInliner::inline([
                new MethodCallRename(DatabaseRecordList::class, 'requestUri', 'listURL'),
            ]),
        ]]);
};
