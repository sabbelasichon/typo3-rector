<?php

declare(strict_types=1);

use Rector\Transform\Rector\StaticCall\StaticCallToFuncCallRector;
use Rector\Transform\ValueObject\StaticCallToFuncCall;
use Ssch\TYPO3Rector\Rector\v11\v0\DateTimeAspectInsteadOfGlobalsExecTimeRector;
use Ssch\TYPO3Rector\Rector\v11\v0\ExtbaseControllerActionsMustReturnResponseInterfaceRector;
use Ssch\TYPO3Rector\Rector\v11\v0\ForwardResponseInsteadOfForwardMethodRector;
use Ssch\TYPO3Rector\Rector\v11\v0\GetClickMenuOnIconTagParametersRector;
use Ssch\TYPO3Rector\Rector\v11\v0\RemoveAddQueryStringMethodRector;
use Ssch\TYPO3Rector\Rector\v11\v0\RemoveLanguageModeMethodsFromTypo3QuerySettingsRector;
use Ssch\TYPO3Rector\Rector\v11\v0\SubstituteConstantsModeAndRequestTypeRector;
use Ssch\TYPO3Rector\Rector\v11\v0\UniqueListFromStringUtilityRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set(ForwardResponseInsteadOfForwardMethodRector::class);
    $services->set(DateTimeAspectInsteadOfGlobalsExecTimeRector::class);
    $services->set(UniqueListFromStringUtilityRector::class);
    $services->set(GetClickMenuOnIconTagParametersRector::class);
    $services->set(RemoveAddQueryStringMethodRector::class);
    $services->set(ExtbaseControllerActionsMustReturnResponseInterfaceRector::class);
    $services->set(SubstituteConstantsModeAndRequestTypeRector::class);
    $services->set(RemoveLanguageModeMethodsFromTypo3QuerySettingsRector::class);
    $services->set(StaticCallToFuncCallRector::class)
        ->call('configure', [
            [
                StaticCallToFuncCallRector::STATIC_CALLS_TO_FUNCTIONS => ValueObjectInliner::inline([
                    new StaticCallToFuncCall('TYPO3\CMS\Core\Utility\StringUtility', 'beginsWith', 'str_starts_with'),
                    new StaticCallToFuncCall('TYPO3\CMS\Core\Utility\StringUtility', 'endsWith', 'str_ends_with'),
                    new StaticCallToFuncCall('TYPO3\CMS\Core\Utility\GeneralUtility', 'isFirstPartOfStr', 'str_starts_with'),
                    new StaticCallToFuncCall('TYPO3\CMS\Core\Utility\GeneralUtility', 'isAbsPath', 'isAbsolutePath'),
                ]),
            ],
        ]);
};
