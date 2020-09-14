<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rector\Renaming\Rector\StaticCall\RenameStaticMethodRector;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');

    $services = $containerConfigurator->services();

    $services->set(RenameStaticMethodRector::class)
        ->call('configure', [[
            RenameStaticMethodRector::OLD_TO_NEW_METHODS_BY_CLASSES => [
                new Rector\Renaming\ValueObject\RenameStaticMethod('TYPO3\CMS\Core\Utility\GeneralUtility', 'isRunningOnCgiServerApi', 'TYPO3\CMS\Core\Core\Environment', 'isRunningOnCgiServer')]
        ]]);


};
