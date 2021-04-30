<?php

use Rector\Core\Configuration\Option;
use Ssch\TYPO3Rector\ComposerPackages\Rector\AddReplacePackageRector;
use Ssch\TYPO3Rector\ValueObject\ReplacePackage;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../config/config.php');

    $services = $containerConfigurator->services();

    $replacePackages = [new ReplacePackage('typo3-ter/news', 'georgringer/news')];

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $services->set(AddReplacePackageRector::class)
        ->call('setReplacePackages', [ValueObjectInliner::inline($replacePackages)]);
};
