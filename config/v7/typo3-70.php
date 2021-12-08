<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use Ssch\TYPO3Rector\Rector\v7\v0\RemoveMethodCallConnectDbRector;
use Ssch\TYPO3Rector\Rector\v7\v0\RemoveMethodCallLoadTcaRector;
use Ssch\TYPO3Rector\Rector\v7\v0\TypeHandlingServiceToTypeHandlingUtilityRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set(RemoveMethodCallConnectDbRector::class);
    $services->set(RemoveMethodCallLoadTcaRector::class);
    $services->set('rename_class_templates_to_document_template')
        ->class(RenameClassRector::class)
        ->configure([
            'TYPO3\CMS\Backend\Template\MediumDocumentTemplate' => 'TYPO3\CMS\Backend\Template\DocumentTemplate',
            'TYPO3\CMS\Backend\Template\SmallDocumentTemplate' => 'TYPO3\CMS\Backend\Template\DocumentTemplate',
            'TYPO3\CMS\Backend\Template\StandardDocumentTemplate' => 'TYPO3\CMS\Backend\Template\DocumentTemplate',
            'TYPO3\CMS\Backend\Template\BigDocumentTemplate' => 'TYPO3\CMS\Backend\Template\DocumentTemplate',
        ]);
    $services->set('rename_static_method_generalUtility_int_from_ver_to_convert_version_number_to_integer')
        ->class(RenameStaticMethodRector::class)
        ->configure([
                new RenameStaticMethod(
                    'TYPO3\CMS\Core\Utility\GeneralUtility',
                    'int_from_ver',
                    'TYPO3\CMS\Core\Utility\VersionNumberUtility',
                    'convertVersionNumberToInteger'
                ),
        ]);
    $services->set(TypeHandlingServiceToTypeHandlingUtilityRector::class);
    $services->set('rename_method_typo3_query_settings')
        ->class(RenameMethodRector::class)
        ->configure([
            new MethodCallRename(
                'TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettingsInterface',
                'setSysLanguageUid',
                'setLanguageUid'
            ),
            new MethodCallRename(
                'TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettingsInterface',
                'getSysLanguageUid',
                'getLanguageUid'
            ),
            new MethodCallRename('TYPO3\CMS\Extbase\Object\ObjectManagerInterface', 'create', 'get'),
        ]);
};
