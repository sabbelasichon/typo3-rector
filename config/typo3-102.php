<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\Backend\Utility\UseTypo3InformationForCopyRightNoticeRector;
use Ssch\TYPO3Rector\Rector\Core\ExcludeServiceKeysToArrayRector;
use Ssch\TYPO3Rector\Rector\Core\Utility\MoveApplicationContextToEnvironmentApiRector;
use Ssch\TYPO3Rector\Rector\Extbase\InjectEnvironmentServiceIfNeededInResponseRector;
use Ssch\TYPO3Rector\Rector\Extbase\UseActionControllerRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');

    $services = $containerConfigurator->services();

    $services->set(MoveApplicationContextToEnvironmentApiRector::class);

    $services->set(ExcludeServiceKeysToArrayRector::class);

    $services->set(UseActionControllerRector::class);

    $services->set(UseTypo3InformationForCopyRightNoticeRector::class);

    $services->set(InjectEnvironmentServiceIfNeededInResponseRector::class);
};
