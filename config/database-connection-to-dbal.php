<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Ssch\TYPO3Rector\Rector\Core\Database\DatabaseConnectionToDbalRector;
use Ssch\TYPO3Rector\Rector\Core\Database\Refactorings\DatabaseConnectionExecInsertQueryRefactoring;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');
    $services = $containerConfigurator->services();

    $services->set(DatabaseConnectionExecInsertQueryRefactoring::class)->autowire(true)->tag(
        'database.dbal.refactoring'
    );
    $services->set(DatabaseConnectionToDbalRector::class)->args(
        [service(Typo3NodeResolver::class), tagged_iterator('database.dbal.refactoring')]
    );
};
