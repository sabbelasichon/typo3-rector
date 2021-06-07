<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;

use Ssch\TYPO3Rector\Rector\v9\v4\AdditionalFieldProviderRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../../../../../../config/config_test.php');
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    $services = $containerConfigurator->services();
    $services->set(AdditionalFieldProviderRector::class);
};
