<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use BadMethodCallException;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;
use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

final class DependencyInjection
{
    public static function service(string $serviceId): ReferenceConfigurator
    {
        if (function_exists('Symfony\Component\DependencyInjection\Loader\Configurator\service')) {
            return service($serviceId);
        }

        if (function_exists('Symfony\Component\DependencyInjection\Loader\Configurator\ref')) {
            return ref($serviceId);
        }

        throw new BadMethodCallException('Cannot resolve one of the symfony di functions ref or service');
    }
}
