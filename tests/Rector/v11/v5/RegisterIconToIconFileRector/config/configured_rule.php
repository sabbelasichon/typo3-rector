<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Set\Typo3SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../../../config/config_test.php');
    $containerConfigurator->import(Typo3SetList::REGISTER_ICONS_TO_ICON);
};
