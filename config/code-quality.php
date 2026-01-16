<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\CodeQuality\General\AddAutoconfigureAttributeToClassRector;
use Ssch\TYPO3Rector\CodeQuality\General\AddErrorCodeToExceptionRector;
use Ssch\TYPO3Rector\CodeQuality\General\ConvertImplicitVariablesToExplicitGlobalsRector;
use Ssch\TYPO3Rector\CodeQuality\General\ExtEmConfRector;
use Ssch\TYPO3Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector;
use Ssch\TYPO3Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector;
use Ssch\TYPO3Rector\CodeQuality\General\MoveExtensionManagementUtilityAddStaticFileIntoTCAOverridesRector;
use Ssch\TYPO3Rector\CodeQuality\General\MoveExtensionManagementUtilityAddToAllTCAtypesIntoTCAOverridesRector;
use Ssch\TYPO3Rector\CodeQuality\General\MoveExtensionUtilityRegisterPluginIntoTCAOverridesRector;
use Ssch\TYPO3Rector\CodeQuality\General\UseExtensionKeyInLocalizationUtilityRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/config.php');
    $rectorConfig
        ->ruleWithConfiguration(ExtEmConfRector::class, [
            ExtEmConfRector::ADDITIONAL_VALUES_TO_BE_REMOVED => [
                '_md5_values_when_last_written',

                // https://docs.typo3.org/m/typo3/reference-coreapi/8.7/en-us/ExtensionArchitecture/DeclarationFile/Index.html#deprecated-configuration
                'dependencies',
                'conflicts',
                'suggests',
                'docPath',
                'CGLcompliance',
                'CGLcompliance_note',
                'private',
                'download_password',
                'shy',
                'loadOrder',
                'priority',
                'internal',
                'modify_tables',
                'module',
                'lockType',
                'TYPO3_version',
                'PHP_version',

                // https://docs.typo3.org/m/typo3/reference-coreapi/9.5/en-us/ExtensionArchitecture/DeclarationFile/Index.html#deprecated-configuration
                'createDirs', // Deprecated since version 9.5
                'uploadfolder', // Deprecated since version 9.5

                // https://docs.typo3.org/m/typo3/reference-coreapi/12.4/en-us/ExtensionArchitecture/FileStructure/ExtEmconf.html#confval-ext-emconf-clearcacheonload
                //'clearCacheOnLoad', // Deprecated since version 12.1
            ],
        ]);
    $rectorConfig->ruleWithConfiguration(GeneralUtilityMakeInstanceToConstructorPropertyRector::class, [
        GeneralUtilityMakeInstanceToConstructorPropertyRector::ALLOWED_CLASSES => [
            'TYPO3\CMS\Backend\CodeEditor\CodeEditor',
            'TYPO3\CMS\Backend\CodeEditor\Registry\AddonRegistry',
            'TYPO3\CMS\Backend\CodeEditor\Registry\ModeRegistry',
            'TYPO3\CMS\Backend\Routing\UriBuilder',
            'TYPO3\CMS\Backend\View\BackendLayout\DataProviderCollection',
            'TYPO3\CMS\Backend\View\BackendLayoutView',
            'TYPO3\CMS\Beuser\Domain\Repository\BackendUserGroupRepository',
            'TYPO3\CMS\Beuser\Domain\Repository\BackendUserRepository',
            'TYPO3\CMS\Beuser\Domain\Repository\FileMountRepository',
            'TYPO3\CMS\Core\Cache\CacheManager',
            'TYPO3\CMS\Core\Composer\PackageArtifactBuilder',
            'TYPO3\CMS\Core\Configuration\Features',
            'TYPO3\CMS\Core\Console\CommandRegistry',
            'TYPO3\CMS\Core\Context\Context',
            'TYPO3\CMS\Core\Crypto\HashService',
            'TYPO3\CMS\Core\Database\ConnectionPool',
            'TYPO3\CMS\Core\Error\DebugExceptionHandler',
            'TYPO3\CMS\Core\Error\ProductionExceptionHandler',
            'TYPO3\CMS\Core\EventDispatcher\EventDispatcher',
            'TYPO3\CMS\Core\Html\DefaultSanitizerBuilder',
            'TYPO3\CMS\Core\Imaging\IconFactory',
            'TYPO3\CMS\Core\Imaging\IconRegistry',
            'TYPO3\CMS\Core\LinkHandling\LinkService',
            'TYPO3\CMS\Core\Localization\LanguageServiceFactory',
            'TYPO3\CMS\Core\Localization\Locales',
            'TYPO3\CMS\Core\Locking\LockFactory',
            'TYPO3\CMS\Core\Log\LogManager',
            'TYPO3\CMS\Core\Mail\MemorySpool',
            'TYPO3\CMS\Core\Mail\TransportFactory',
            'TYPO3\CMS\Core\Messaging\FlashMessageService',
            'TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry',
            'TYPO3\CMS\Core\Package\FailsafePackageManager',
            'TYPO3\CMS\Core\Package\PackageManager',
            'TYPO3\CMS\Core\Package\UnitTestPackageManager',
            'TYPO3\CMS\Core\Page\AssetCollector',
            'TYPO3\CMS\Core\Page\ImportMapFactory',
            'TYPO3\CMS\Core\Page\PageRenderer',
            'TYPO3\CMS\Core\PageTitle\PageTitleProviderManager',
            'TYPO3\CMS\Core\PageTitle\RecordPageTitleProvider',
            'TYPO3\CMS\Core\PageTitle\RecordTitleProvider',
            'TYPO3\CMS\Core\Registry',
            'TYPO3\CMS\Core\Resource\Collection\FileCollectionRegistry',
            'TYPO3\CMS\Core\Resource\Driver\DriverRegistry',
            'TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry',
            'TYPO3\CMS\Core\Resource\Processing\TaskTypeRegistry',
            'TYPO3\CMS\Core\Resource\Rendering\AudioTagRenderer',
            'TYPO3\CMS\Core\Resource\Rendering\RendererRegistry',
            'TYPO3\CMS\Core\Resource\Rendering\VideoTagRenderer',
            'TYPO3\CMS\Core\Resource\Rendering\VimeoRenderer',
            'TYPO3\CMS\Core\Resource\Rendering\YouTubeRenderer',
            'TYPO3\CMS\Core\Resource\ResourceFactory',
            'TYPO3\CMS\Core\Resource\TextExtraction\TextExtractorRegistry',
            'TYPO3\CMS\Core\Routing\SiteMatcher',
            'TYPO3\CMS\Core\Schema\TcaSchemaFactory',
            'TYPO3\CMS\Core\Service\Archive\ZipService',
            'TYPO3\CMS\Core\Service\DependencyOrderingService',
            'TYPO3\CMS\Core\Service\FlexFormService',
            'TYPO3\CMS\Core\Service\MarkerBasedTemplateService',
            'TYPO3\CMS\Core\Session\SessionManager',
            'TYPO3\CMS\Core\Site\SiteFinder',
            'TYPO3\CMS\Core\TimeTracker\TimeTracker',
            'TYPO3\CMS\Extbase\Configuration\ConfigurationManager',
            'TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface',
            'TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfigurationService',
            'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
            'TYPO3\CMS\Extbase\Persistence\ClassesConfiguration',
            'TYPO3\CMS\Extbase\Persistence\Generic\Mapper\ColumnMapFactory',
            'TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager',
            'TYPO3\CMS\Extbase\Persistence\Generic\Qom\QueryObjectModelFactory',
            'TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbBackend',
            'TYPO3\CMS\Extbase\Persistence\Repository',
            'TYPO3\CMS\Extbase\Property\PropertyMapper',
            'TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationBuilder',
            'TYPO3\CMS\Extbase\Property\TypeConverter\ArrayConverter',
            'TYPO3\CMS\Extbase\Property\TypeConverter\BooleanConverter',
            'TYPO3\CMS\Extbase\Property\TypeConverter\CoreTypeConverter',
            'TYPO3\CMS\Extbase\Property\TypeConverter\CountryConverter',
            'TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter',
            'TYPO3\CMS\Extbase\Property\TypeConverter\EnumConverter',
            'TYPO3\CMS\Extbase\Property\TypeConverter\FileConverter',
            'TYPO3\CMS\Extbase\Property\TypeConverter\FileReferenceConverter',
            'TYPO3\CMS\Extbase\Property\TypeConverter\FloatConverter',
            'TYPO3\CMS\Extbase\Property\TypeConverter\FolderConverter',
            'TYPO3\CMS\Extbase\Property\TypeConverter\IntegerConverter',
            'TYPO3\CMS\Extbase\Property\TypeConverter\ObjectConverter',
            'TYPO3\CMS\Extbase\Property\TypeConverter\ObjectStorageConverter',
            'TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter',
            'TYPO3\CMS\Extbase\Property\TypeConverter\StringConverter',
            'TYPO3\CMS\Extbase\Reflection\ReflectionService',
            'TYPO3\CMS\Extbase\Service\CacheService',
            'TYPO3\CMS\Extbase\Service\ExtensionService',
            'TYPO3\CMS\Extbase\Service\FileHandlingService',
            'TYPO3\CMS\Extbase\Service\ImageService',
            'TYPO3\CMS\Extbase\Validation\ValidatorResolver',
            'TYPO3\CMS\Frontend\ContentObject\Menu\MenuContentObjectFactory',
            'TYPO3\CMS\Frontend\Typolink\PageLinkBuilder',
            'TYPO3\CMS\Scheduler\Scheduler',
            'TYPO3\CMS\Seo\PageTitle\SeoTitlePageTitleProvider',
            'TYPO3\CMS\Workspaces\Service\Dependency\CollectionService',
            'TYPO3\CMS\Workspaces\Service\HistoryService',
        ],
    ]);
    // $rectorConfig->rule(RemoveTypo3VersionChecksRector::class); this rule is not activated by default as it depends on the (configured) TYPO3 version!
    $rectorConfig->rule(AddErrorCodeToExceptionRector::class);
    $rectorConfig->rule(ConvertImplicitVariablesToExplicitGlobalsRector::class);
    $rectorConfig->rule(InjectMethodToConstructorInjectionRector::class);
    $rectorConfig->rule(MoveExtensionManagementUtilityAddStaticFileIntoTCAOverridesRector::class);
    $rectorConfig->rule(MoveExtensionManagementUtilityAddToAllTCAtypesIntoTCAOverridesRector::class);
    $rectorConfig->rule(MoveExtensionUtilityRegisterPluginIntoTCAOverridesRector::class);
    $rectorConfig->rule(UseExtensionKeyInLocalizationUtilityRector::class);
    $rectorConfig->rule(AddAutoconfigureAttributeToClassRector::class);
};
