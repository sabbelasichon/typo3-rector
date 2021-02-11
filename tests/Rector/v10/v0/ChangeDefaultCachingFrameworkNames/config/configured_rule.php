<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Ssch\TYPO3Rector\Rector\v10\v0\ChangeDefaultCachingFrameworkNamesRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHPSTAN_FOR_RECTOR_PATH, __DIR__ . '/../../../../../../utils/phpstan/config/extension.neon');

    $services = $containerConfigurator->services();
    $services->set(ChangeDefaultCachingFrameworkNamesRector::class);
};
