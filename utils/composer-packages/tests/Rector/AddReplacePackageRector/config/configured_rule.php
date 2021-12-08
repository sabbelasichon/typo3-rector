<?php

declare(strict_types=1);

use Rector\Composer\ValueObject\RenamePackage;

use Rector\Core\Configuration\Option;
use Ssch\TYPO3Rector\ComposerPackages\Rector\AddReplacePackageRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../config/config.php');

    $services = $containerConfigurator->services();

    $replacePackages = [new RenamePackage('typo3-ter/news', 'georgringer/news')];

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    $services->set(AddReplacePackageRector::class)
        ->configure([$replacePackages]);
};
