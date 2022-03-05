<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\Rector\Experimental\ObjectManagerGetToConstructorInjectionRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_74);

    $containerConfigurator->import(__DIR__ . '/../../../../../config/config_test.php');
    $services = $containerConfigurator->services();
    $services->set(ObjectManagerGetToConstructorInjectionRector::class);
};
