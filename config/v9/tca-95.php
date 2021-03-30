<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\v9\v0\RemoveOptionLocalizeChildrenAtParentLocalizationRector;
use Ssch\TYPO3Rector\Rector\v9\v5\RefactorTypeInternalTypeFileAndFileReferenceToFalRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');
    $services = $containerConfigurator->services();
    $services->set(RemoveOptionLocalizeChildrenAtParentLocalizationRector::class);
    $services->set(RefactorTypeInternalTypeFileAndFileReferenceToFalRector::class);
};
