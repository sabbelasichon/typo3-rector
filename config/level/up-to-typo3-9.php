<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Set\Typo3LevelSetList;
use Ssch\TYPO3Rector\Set\Typo3SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(Typo3LevelSetList::UP_TO_TYPO3_8);
    $containerConfigurator->import(Typo3SetList::TYPO3_95);
};
