<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Set\Typo3SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(Typo3SetList::EXTBASE_COMMAND_CONTROLLERS_TO_SYMFONY_COMMANDS);
};
