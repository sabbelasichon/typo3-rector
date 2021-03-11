<?php

declare(strict_types=1);

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
};
