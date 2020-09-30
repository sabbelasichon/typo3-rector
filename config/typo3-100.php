<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Namespace_\RenameNamespaceRector;
use Ssch\TYPO3Rector\Rector\Core\Resource\UseMetaDataAspectRector;
use Ssch\TYPO3Rector\Rector\Core\Utility\RefactorIdnaEncodeMethodToNativeFunctionRector;
use Ssch\TYPO3Rector\Rector\Extbase\RemovePropertyExtensionNameRector;
use Ssch\TYPO3Rector\Rector\Extbase\Utility\UseNativePhpHex2binMethodRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');

    $services = $containerConfigurator->services();

    $services->set(RemovePropertyExtensionNameRector::class);

    $services->set(UseNativePhpHex2binMethodRector::class);

    $services->set(RefactorIdnaEncodeMethodToNativeFunctionRector::class);

    $services->set(RenameNamespaceRector::class)
        ->call('configure', [
            RenameNamespaceRector::OLD_TO_NEW_NAMESPACES => [
                [
                    'TYPO3\CMS\Backend\Controller\File' => 'TYPO3\CMS\Filelist\Controller\File'
                ],
            ],
        ]);

    $services->set(UseMetaDataAspectRector::class);
};
