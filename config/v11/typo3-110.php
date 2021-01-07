<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\v11\v0\DateTimeAspectInsteadOfGlobalsExecTimeRector;
use Ssch\TYPO3Rector\Rector\v11\v0\ExtbaseControllerActionsMustReturnResponseInterfaceRector;
use Ssch\TYPO3Rector\Rector\v11\v0\ForwardResponseInsteadOfForwardMethodRector;
use Ssch\TYPO3Rector\Rector\v11\v0\GetClickMenuOnIconTagParametersRector;
use Ssch\TYPO3Rector\Rector\v11\v0\RemoveAddQueryStringMethodRector;
use Ssch\TYPO3Rector\Rector\v11\v0\SubstituteConstantsModeAndRequestTypeRector;
use Ssch\TYPO3Rector\Rector\v11\v0\UniqueListFromStringUtilityRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');
    $services = $containerConfigurator->services();
    $services->set(ForwardResponseInsteadOfForwardMethodRector::class);
    $services->set(DateTimeAspectInsteadOfGlobalsExecTimeRector::class);
    $services->set(UniqueListFromStringUtilityRector::class);
    $services->set(GetClickMenuOnIconTagParametersRector::class);
    $services->set(RemoveAddQueryStringMethodRector::class);
    $services->set(ExtbaseControllerActionsMustReturnResponseInterfaceRector::class);
    $services->set(SubstituteConstantsModeAndRequestTypeRector::class);
};
