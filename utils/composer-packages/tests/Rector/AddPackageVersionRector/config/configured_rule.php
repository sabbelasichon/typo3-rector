<?php

declare(strict_types=1);

use Rector\Composer\ValueObject\PackageAndVersion;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\ValueObjectInliner;
use Ssch\TYPO3Rector\ComposerPackages\Rector\AddPackageVersionRector;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ExtensionVersion;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\Typo3Version;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $rectorConfig->import(__DIR__ . '/../../../../config/config.php');

    $services = $containerConfigurator->services();

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    $extension = new ExtensionVersion(new PackageAndVersion('foo/bar', '1.0'), [new Typo3Version('9.5.99')]);
    $services->set(AddPackageVersionRector::class)
        ->call('setExtension', [ValueObjectInliner::inline($extension)]);
};
