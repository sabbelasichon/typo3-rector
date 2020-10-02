<?php

declare(strict_types=1);

use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;
use Rector\Renaming\ValueObject\RenameStaticMethod;
use function Rector\SymfonyPhpConfig\inline_value_objects;
use Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector;
use Ssch\TYPO3Rector\Rector\v10\v4\SubstituteGeneralUtilityMethodsWithNativePhpFunctionsRector;
use Ssch\TYPO3Rector\Rector\v10\v4\UnifiedFileNameValidatorRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');

    $services = $containerConfigurator->services();

    $services->set(UnifiedFileNameValidatorRector::class);
    $services->set(SubstituteGeneralUtilityMethodsWithNativePhpFunctionsRector::class);

    $services->set(RenameStaticMethodRector::class)
        ->call('configure', [
            [
                RenameStaticMethodRector::OLD_TO_NEW_METHODS_BY_CLASSES => inline_value_objects([
                    new RenameStaticMethod(
                       GeneralUtility::class,
                       'isRunningOnCgiServerApi',
                       Environment::class,
                       'isRunningOnCgiServer'
                   ),
                ]),
            ],
        ]);

    $services->set(RenameClassMapAliasRector::class)
        ->call('configure', [
            [
                RenameClassMapAliasRector::CLASS_ALIAS_MAPS => [
                    __DIR__ . '/../Migrations/TYPO3/10.4/typo3/sysext/backend/Migrations/Code/ClassAliasMap.php',
                    __DIR__ . '/../Migrations/TYPO3/10.4/typo3/sysext/core/Migrations/Code/ClassAliasMap.php',
                ],
            ],
        ]);
};
