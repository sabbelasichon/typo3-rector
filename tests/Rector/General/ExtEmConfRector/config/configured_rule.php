<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\General\ExtEmConfRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../../config/config_test.php');

    $services = $containerConfigurator->services();
    $services->set(ExtEmConfRector::class)
        ->configure([
            ExtEmConfRector::TYPO3_VERSION_CONSTRAINT => '9.5.0-10.4.99',
            ExtEmConfRector::ADDITIONAL_VALUES_TO_BE_REMOVED => ['createDirs', 'uploadfolder'],
        ]);
};
