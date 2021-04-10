<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Namespace_\RenameNamespaceRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Transform\Rector\MethodCall\MethodCallToStaticCallRector;
use Rector\Transform\ValueObject\MethodCallToStaticCall;
use Ssch\TYPO3Rector\Rector\General\ExtEmConfRector;
use Ssch\TYPO3Rector\Rector\v10\v0\BackendUtilityGetViewDomainToPageRouterRector;
use Ssch\TYPO3Rector\Rector\v10\v0\ChangeDefaultCachingFrameworkNamesRector;
use Ssch\TYPO3Rector\Rector\v10\v0\ConfigurationManagerAddControllerConfigurationMethodRector;
use Ssch\TYPO3Rector\Rector\v10\v0\ForceTemplateParsingInTsfeAndTemplateServiceRector;
use Ssch\TYPO3Rector\Rector\v10\v0\RefactorIdnaEncodeMethodToNativeFunctionRector;
use Ssch\TYPO3Rector\Rector\v10\v0\RemoveFormatConstantsEmailFinisherRector;
use Ssch\TYPO3Rector\Rector\v10\v0\RemovePropertyExtensionNameRector;
use Ssch\TYPO3Rector\Rector\v10\v0\SetSystemLocaleFromSiteLanguageRector;
use Ssch\TYPO3Rector\Rector\v10\v0\SwiftMailerBasedMailMessageToMailerBasedMessageRector;
use Ssch\TYPO3Rector\Rector\v10\v0\UseControllerClassesInExtbasePluginsAndModulesRector;

use Ssch\TYPO3Rector\Rector\v10\v0\UseMetaDataAspectRector;
use Ssch\TYPO3Rector\Rector\v10\v0\UseNativePhpHex2binMethodRector;
use Ssch\TYPO3Rector\Rector\v10\v0\UseTwoLetterIsoCodeFromSiteLanguageRector;
use Ssch\TYPO3Rector\Yaml\Form\Transformer\EmailFinisherTransformer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set(RemovePropertyExtensionNameRector::class);
    $services->set(UseNativePhpHex2binMethodRector::class);
    $services->set(RefactorIdnaEncodeMethodToNativeFunctionRector::class);
    $services->set('rename_namespace_backend_controller_file_to_filelist_controller_file')
        ->class(RenameNamespaceRector::class)
        ->call(
        'configure',
        [[
            RenameNamespaceRector::OLD_TO_NEW_NAMESPACES => [
                'TYPO3\CMS\Backend\Controller\File' => 'TYPO3\CMS\Filelist\Controller\File',

            ],
        ]]
    );
    $services->set(UseMetaDataAspectRector::class);
    $services->set(ForceTemplateParsingInTsfeAndTemplateServiceRector::class);
    $services->set(BackendUtilityGetViewDomainToPageRouterRector::class);
    $services->set(SetSystemLocaleFromSiteLanguageRector::class);
    $services->set(ConfigurationManagerAddControllerConfigurationMethodRector::class);
    $services->set(RemoveFormatConstantsEmailFinisherRector::class);
    $services->set(UseTwoLetterIsoCodeFromSiteLanguageRector::class);
    $services->set(UseControllerClassesInExtbasePluginsAndModulesRector::class);
    $services->set(ChangeDefaultCachingFrameworkNamesRector::class);
    $services->set(ExtEmConfRector::class)
        ->call('configure', [[
            ExtEmConfRector::ADDITIONAL_VALUES_TO_BE_REMOVED => ['createDirs', 'uploadfolder'],
        ]]);
    $services->set(SwiftMailerBasedMailMessageToMailerBasedMessageRector::class);

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

    $services->set(EmailFinisherTransformer::class);
};
