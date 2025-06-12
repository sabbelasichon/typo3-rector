<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
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

                // https://docs.typo3.org/m/typo3/reference-coreapi/12.4/en-us/ExtensionArchitecture/FileStructure/ExtEmconf.html
                //'clearCacheOnLoad', // Deprecated since version 12.1
            ],
        ]);
    $rectorConfig->rule(AddErrorCodeToExceptionRector::class);
    $rectorConfig->rule(ConvertImplicitVariablesToExplicitGlobalsRector::class);
    $rectorConfig->rule(InjectMethodToConstructorInjectionRector::class);
    $rectorConfig->rule(MoveExtensionManagementUtilityAddStaticFileIntoTCAOverridesRector::class);
    $rectorConfig->rule(MoveExtensionManagementUtilityAddToAllTCAtypesIntoTCAOverridesRector::class);
    $rectorConfig->rule(MoveExtensionUtilityRegisterPluginIntoTCAOverridesRector::class);
    $rectorConfig->rule(UseExtensionKeyInLocalizationUtilityRector::class);
    $rectorConfig->rule(GeneralUtilityMakeInstanceToConstructorPropertyRector::class);
};
