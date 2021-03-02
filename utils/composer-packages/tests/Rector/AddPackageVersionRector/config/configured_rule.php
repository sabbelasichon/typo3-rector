<?php

use Rector\Composer\ValueObject\PackageAndVersion;
use Ssch\TYPO3Rector\ComposerPackages\Rector\AddPackageVersionRector;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ExtensionVersion;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\Typo3Version;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../config/config.php');

    $services = $containerConfigurator->services();

    $extension = new ExtensionVersion(new PackageAndVersion('foo/bar', '1.0'), [new Typo3Version('9.5.99')]);
    $services->set(AddPackageVersionRector::class)
        ->call('setExtension', [ValueObjectInliner::inline($extension)]);
};
