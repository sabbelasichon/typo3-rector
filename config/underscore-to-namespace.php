<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');

    $services = $containerConfigurator->services();

    $services->set(RenameClassMapAliasRector::class)
        ->call('configure', [[
            RenameClassMapAliasRector::CLASS_ALIAS_MAPS => [__DIR__ . '/../Migrations/Code/ClassAliasMap.php'],
        ]]);
};
