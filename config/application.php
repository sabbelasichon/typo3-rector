<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Console\Application\Typo3RectorConsoleApplication;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../utils/generator/config/config.php', null, true);

    $services = $containerConfigurator->services();
    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->set(Typo3RectorConsoleApplication::class);
    $services->set(ConsoleOutput::class);
    $services->alias(OutputInterface::class, ConsoleOutput::class);
};
