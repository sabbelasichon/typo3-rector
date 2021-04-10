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
use TYPO3\CMS\Backend\Template\BigDocumentTemplate;
use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Backend\Template\MediumDocumentTemplate;
use TYPO3\CMS\Backend\Template\SmallDocumentTemplate;
use TYPO3\CMS\Backend\Template\StandardDocumentTemplate;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set(RemoveMethodCallConnectDbRector::class);
    $services->set(RemoveMethodCallLoadTcaRector::class);
    $services->set('rename_class_templates_to_document_template')
        ->class(RenameClassRector::class)
        ->call('configure', [[
            RenameClassRector::OLD_TO_NEW_CLASSES => [
                MediumDocumentTemplate::class => DocumentTemplate::class,
                SmallDocumentTemplate::class => DocumentTemplate::class,
                StandardDocumentTemplate::class => DocumentTemplate::class,
                BigDocumentTemplate::class => DocumentTemplate::class,
            ],
        ]]);
    $services->set('rename_static_method_generalUtility_int_from_ver_to_convert_version_number_to_integer')
        ->class(RenameStaticMethodRector::class)
        ->call(
        'configure',
        [[
            RenameStaticMethodRector::OLD_TO_NEW_METHODS_BY_CLASSES => ValueObjectInliner::inline([
                new RenameStaticMethod(
                    GeneralUtility::class,
                    'int_from_ver',
                    VersionNumberUtility::class,
                    'convertVersionNumberToInteger'
                ),
            ]),
        ]]
    );
    $services->set(TypeHandlingServiceToTypeHandlingUtilityRector::class);
    $services->set('rename_method_typo3_query_settings')
        ->class(RenameMethodRector::class)
        ->call('configure', [[
            RenameMethodRector::METHOD_CALL_RENAMES => ValueObjectInliner::inline([
                new MethodCallRename(Typo3QuerySettings::class, 'setSysLanguageUid', 'setLanguageUid'),
                new MethodCallRename(Typo3QuerySettings::class, 'getSysLanguageUid', 'getLanguageUid'),
                new MethodCallRename(ObjectManager::class, 'create', 'get'),
            ]),
        ]]);
};
