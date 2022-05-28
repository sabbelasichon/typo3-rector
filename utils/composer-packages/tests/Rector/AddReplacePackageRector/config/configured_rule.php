<?php

declare(strict_types=1);

use Rector\Composer\ValueObject\RenamePackage;
use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\ComposerPackages\NodeDecorator\AddReplacePackageDecorator;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../config/config.php');

    $rectorConfig->importNames();

    $rectorConfig->ruleWithConfiguration(AddReplacePackageDecorator::class, [
        new RenamePackage('typo3-ter/news', 'georgringer/news'),
    ]);
};
