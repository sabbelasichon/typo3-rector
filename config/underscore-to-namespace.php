<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $rectorConfig->import(__DIR__ . '/config.php');

    $services = $containerConfigurator->services();

    $services->set(RenameClassMapAliasRector::class)
        ->configure([__DIR__ . '/../Migrations/Code/ClassAliasMap.php']);
};
