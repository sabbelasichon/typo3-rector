<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Ssch\TYPO3Rector\TYPO313\v4\MigratePluginContentElementAndPluginSubtypesRector;
use Ssch\TYPO3Rector\TYPO313\v4\MigratePluginContentElementAndPluginSubtypesSwapArgsRector;
use Ssch\TYPO3Rector\TYPO313\v4\MigratePluginContentElementAndPluginSubtypesTCARector;
use Ssch\TYPO3Rector\TYPO313\v4\RemoveTcaSubTypesExcludeListTCARector;
use Ssch\TYPO3Rector\TYPO313\v4\RenameTableOptionsAndCollateConnectionConfigurationRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(
        RenameClassConstFetchRector::class,
        [
            new RenameClassAndConstFetch(
                'TYPO3\CMS\Core\Database\Schema\Types\EnumType',
                'TYPE',
                'Doctrine\DBAL\Types\Types',
                'ENUM'
            ),
        ]
    );

    $rectorConfig->rule(MigratePluginContentElementAndPluginSubtypesRector::class);
    $rectorConfig->rule(MigratePluginContentElementAndPluginSubtypesTCARector::class);
    $rectorConfig->rule(RemoveTcaSubTypesExcludeListTCARector::class);
    $rectorConfig->rule(MigratePluginContentElementAndPluginSubtypesSwapArgsRector::class);
    $rectorConfig->rule(RenameTableOptionsAndCollateConnectionConfigurationRector::class);
};
