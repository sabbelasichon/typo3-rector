<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Ssch\TYPO3Rector\Set\Typo3SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();

    $containerConfigurator->import(Typo3SetList::TYPO3_76);
    $containerConfigurator->import(Typo3SetList::TYPO3_87);
    $containerConfigurator->import(Typo3SetList::TYPO3_95);
    $containerConfigurator->import(Typo3SetList::TYPO3_104);
    $containerConfigurator->import(Typo3SetList::TYPO3_11);

    // Define your target version which you want to support
    $rectorConfig->phpVersion(PhpVersion::PHP_74);
};
