<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Namespace_\RenameNamespaceRector;
use Ssch\TYPO3Rector\Rector\v10\v0\BackendUtilityGetViewDomainToPageRouterRector;
use Ssch\TYPO3Rector\Rector\v10\v0\ChangeDefaultCachingFrameworkNamesRector;
use Ssch\TYPO3Rector\Rector\v10\v0\ConfigurationManagerAddControllerConfigurationMethodRector;
use Ssch\TYPO3Rector\Rector\v10\v0\ForceTemplateParsingInTsfeAndTemplateServiceRector;
use Ssch\TYPO3Rector\Rector\v10\v0\RefactorIdnaEncodeMethodToNativeFunctionRector;
use Ssch\TYPO3Rector\Rector\v10\v0\RemoveFormatConstantsEmailFinisherRector;
use Ssch\TYPO3Rector\Rector\v10\v0\RemovePropertyExtensionNameRector;
use Ssch\TYPO3Rector\Rector\v10\v0\RemoveSeliconFieldPathRector;
use Ssch\TYPO3Rector\Rector\v10\v0\SetSystemLocaleFromSiteLanguageRector;
use Ssch\TYPO3Rector\Rector\v10\v0\UseControllerClassesInExtbasePluginsAndModulesRector;
use Ssch\TYPO3Rector\Rector\v10\v0\UseMetaDataAspectRector;
use Ssch\TYPO3Rector\Rector\v10\v0\UseNativePhpHex2binMethodRector;
use Ssch\TYPO3Rector\Rector\v10\v0\UseTwoLetterIsoCodeFromSiteLanguageRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');
    $services = $containerConfigurator->services();
    $services->set(RemovePropertyExtensionNameRector::class);
    $services->set(UseNativePhpHex2binMethodRector::class);
    $services->set(RefactorIdnaEncodeMethodToNativeFunctionRector::class);
    $services->set(RenameNamespaceRector::class)->call('configure', [
        RenameNamespaceRector::OLD_TO_NEW_NAMESPACES => [[
            'TYPO3\CMS\Backend\Controller\File' => 'TYPO3\CMS\Filelist\Controller\File',
        ]],
        ]);
    $services->set(UseMetaDataAspectRector::class);
    $services->set(ForceTemplateParsingInTsfeAndTemplateServiceRector::class);
    $services->set(BackendUtilityGetViewDomainToPageRouterRector::class);
    $services->set(SetSystemLocaleFromSiteLanguageRector::class);
    $services->set(ConfigurationManagerAddControllerConfigurationMethodRector::class);
    $services->set(RemoveFormatConstantsEmailFinisherRector::class);
    $services->set(UseTwoLetterIsoCodeFromSiteLanguageRector::class);
    $services->set(UseControllerClassesInExtbasePluginsAndModulesRector::class);
    $services->set(ChangeDefaultCachingFrameworkNamesRector::class);
    $services->set(RemoveSeliconFieldPathRector::class);
};
