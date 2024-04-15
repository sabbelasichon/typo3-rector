<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO311\v0\ExtbaseControllerActionsMustReturnResponseInterfaceRector;
use Ssch\TYPO3Rector\TYPO311\v0\ForwardResponseInsteadOfForwardMethodRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->rule(ForwardResponseInsteadOfForwardMethodRector::class);
    $rectorConfig->ruleWithConfiguration(ExtbaseControllerActionsMustReturnResponseInterfaceRector::class, [
        'redirect_methods' => ['redirect', 'redirectToUri', 'additionalRedirectMethod'],
    ]);
};
