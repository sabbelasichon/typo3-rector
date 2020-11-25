<?php

declare(strict_types=1);

use PhpParser\ParserFactory;
use Ssch\TYPO3Rector\Helper\Tca\Refactorings\TcaMigrationVersion10;
use Ssch\TYPO3Rector\Helper\Tca\Refactorings\TcaMigrationVersion7;
use Ssch\TYPO3Rector\Helper\Tca\Refactorings\TcaMigrationVersion8;
use Ssch\TYPO3Rector\Helper\Tca\Refactorings\TcaMigrationVersion9;
use Ssch\TYPO3Rector\Rector\Core\Tca\TcaMigrationRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');
    $services = $containerConfigurator->services();

    $services->set(TcaMigrationRector::class)->args(
        [service(ParserFactory::class), [
            service(TcaMigrationVersion7::class),
            service(TcaMigrationVersion8::class),
            service(TcaMigrationVersion9::class),
            service(TcaMigrationVersion10::class),
        ]]
    );
};
