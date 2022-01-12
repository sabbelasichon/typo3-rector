<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\v11\v4\UseNativeFunctionInsteadOfGeneralUtilityShortMd5Rector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set(UseNativeFunctionInsteadOfGeneralUtilityShortMd5Rector::class);
    $services->set(\Ssch\TYPO3Rector\Rector\v11\v4\ProvideCObjViaMethodRector::class);
    $services->set(\Ssch\TYPO3Rector\Rector\v11\v4\AddSetConfigurationMethodToExceptionHandlerRector::class);
};
