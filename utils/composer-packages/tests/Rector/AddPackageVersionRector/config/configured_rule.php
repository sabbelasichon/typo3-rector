<?php

declare(strict_types=1);

use Rector\Composer\ValueObject\PackageAndVersion;
use Rector\Config\RectorConfig;
use Rector\Core\Configuration\ValueObjectInliner;
use Ssch\TYPO3Rector\ComposerPackages\Rector\AddPackageVersionRector;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ExtensionVersion;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\Typo3Version;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../config/config.php');
    $rectorConfig->importNames();

    $extension = new ExtensionVersion(new PackageAndVersion('foo/bar', '1.0'), [new Typo3Version('9.5.99')]);

    $services = $rectorConfig->services();
    $services->set(AddPackageVersionRector::class)
        ->call('setExtension', [ValueObjectInliner::inline($extension)]);
};
