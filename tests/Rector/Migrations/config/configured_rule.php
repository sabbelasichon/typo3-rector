<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RenameClassMapAliasRector::class)
        ->call('configure', [[
            RenameClassMapAliasRector::CLASS_ALIAS_MAPS => [
                __DIR__ . '/../../../Migrations/TYPO3/9.5/typo3/sysext/fluid/Migrations/Code/ClassAliasMap.php',
            ],
        ]]);
};
