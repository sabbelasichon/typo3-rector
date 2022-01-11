<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\FileProcessor\Composer\Rector\ExtensionComposerRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../../../config/config_test.php');
    $services = $containerConfigurator->services();

    $services->set(ExtensionComposerRector::class)
        ->configure([
            ExtensionComposerRector::TYPO3_VERSION_CONSTRAINT => '^9.5',
        ]);
};
