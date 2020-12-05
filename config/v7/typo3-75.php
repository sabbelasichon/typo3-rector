<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\v7\v5\RemoveIconsInOptionTagsRector;
use Ssch\TYPO3Rector\Rector\v7\v5\UseExtPrefixForTcaIconFileRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');
    $services = $containerConfigurator->services();
    $services->set(RemoveIconsInOptionTagsRector::class);
    $services->set(UseExtPrefixForTcaIconFileRector::class);
};
