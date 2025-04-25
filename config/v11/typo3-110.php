<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Transform\Rector\StaticCall\StaticCallToFuncCallRector;
use Rector\Transform\ValueObject\StaticCallToFuncCall;
use Ssch\TYPO3Rector\TYPO311\v0\DateTimeAspectInsteadOfGlobalsExecTimeRector;
use Ssch\TYPO3Rector\TYPO311\v0\ExtbaseControllerActionsMustReturnResponseInterfaceRector;
use Ssch\TYPO3Rector\TYPO311\v0\ForwardResponseInsteadOfForwardMethodRector;
use Ssch\TYPO3Rector\TYPO311\v0\GetClickMenuOnIconTagParametersRector;
use Ssch\TYPO3Rector\TYPO311\v0\MigrateAbstractUserAuthenticationCreateSessionIdRector;
use Ssch\TYPO3Rector\TYPO311\v0\MigrateAbstractUserAuthenticationGetIdRector;
use Ssch\TYPO3Rector\TYPO311\v0\MigrateAbstractUserAuthenticationGetSessionIdRector;
use Ssch\TYPO3Rector\TYPO311\v0\ReplaceInjectAnnotationWithMethodRector;
use Ssch\TYPO3Rector\TYPO311\v0\SubstituteConstantsModeAndRequestTypeRector;
use Ssch\TYPO3Rector\TYPO311\v0\UniqueListFromStringUtilityRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(ForwardResponseInsteadOfForwardMethodRector::class);
    $rectorConfig->rule(DateTimeAspectInsteadOfGlobalsExecTimeRector::class);
    $rectorConfig->rule(UniqueListFromStringUtilityRector::class);
    $rectorConfig->rule(GetClickMenuOnIconTagParametersRector::class);
    $rectorConfig->ruleWithConfiguration(ExtbaseControllerActionsMustReturnResponseInterfaceRector::class, [
        'redirect_methods' => ['redirect', 'redirectToUri'],
    ]);
    $rectorConfig->rule(SubstituteConstantsModeAndRequestTypeRector::class);
    $rectorConfig
        ->ruleWithConfiguration(StaticCallToFuncCallRector::class, [
            new StaticCallToFuncCall('TYPO3\CMS\Core\Utility\StringUtility', 'beginsWith', 'str_starts_with'),
            new StaticCallToFuncCall('TYPO3\CMS\Core\Utility\StringUtility', 'endsWith', 'str_ends_with'),
            new StaticCallToFuncCall(
                'TYPO3\CMS\Core\Utility\GeneralUtility',
                'isFirstPartOfStr',
                'str_starts_with'
            ),
        ]);
    $rectorConfig->rule(ReplaceInjectAnnotationWithMethodRector::class);
    $rectorConfig->rule(MigrateAbstractUserAuthenticationCreateSessionIdRector::class);
    $rectorConfig->rule(MigrateAbstractUserAuthenticationGetIdRector::class);
    $rectorConfig->rule(MigrateAbstractUserAuthenticationGetSessionIdRector::class);
};
