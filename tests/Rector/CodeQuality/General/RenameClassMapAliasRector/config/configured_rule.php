<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersionFeature;
use Ssch\TYPO3Rector\CodeQuality\General\RenameClassMapAliasRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->importNames();
    $rectorConfig->phpVersion(PhpVersionFeature::CLASSNAME_CONSTANT);
    $rectorConfig->ruleWithConfiguration(RenameClassMapAliasRector::class, [
        __DIR__ . '/../../../../../../Migrations/TYPO3/10.4/typo3/sysext/backend/Migrations/Code/ClassAliasMap.php',
    ]);
};
