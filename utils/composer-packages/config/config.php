<?php

declare(strict_types=1);

use Rector\Composer\ValueObject\RenamePackage;
use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\ComposerPackages\Rector\AddPackageVersionRector;
use Ssch\TYPO3Rector\ComposerPackages\Rector\AddReplacePackageRector;
use Ssch\TYPO3Rector\ComposerPackages\Rector\RemovePackageVersionsRector;
use Symfony\Component\Filesystem\Filesystem;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();
    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->load('Ssch\TYPO3Rector\ComposerPackages\\', __DIR__ . '/../src')
        ->exclude([__DIR__ . '/../src/Rector', __DIR__ . '/../src/ValueObject', __DIR__ . '/../src/Collection']);

    $rectorConfig->rule(RemovePackageVersionsRector::class);
    $rectorConfig->rule(AddPackageVersionRector::class);
    // In order to have no complaints from rector we fill it with some dummy data, the actual packages are created on runtime.
    $rectorConfig->ruleWithConfiguration(AddReplacePackageRector::class, [
        'package' => new RenamePackage('foo/bar', 'foo/baz'),
    ]);

    $services->set(Filesystem::class);
};
