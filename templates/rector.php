<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Ssch\TYPO3Rector\Set\Typo3SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();

    // Define what rule sets will be applied
    $parameters->set(Option::SETS, [
        Typo3SetList::TYPO3_76,
        Typo3SetList::TYPO3_87,
        Typo3SetList::TYPO3_95,
        Typo3SetList::TYPO3_104,
        Typo3SetList::TYPO3_11,
    ]);

    // Define your target version which you want to support
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_72);
};
