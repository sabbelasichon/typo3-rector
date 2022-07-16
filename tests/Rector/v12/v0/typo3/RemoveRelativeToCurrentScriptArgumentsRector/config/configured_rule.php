<?php

declare(strict_types=1);

use Rector\Arguments\Rector\MethodCall\RemoveMethodCallParamRector;
use Rector\Arguments\ValueObject\RemoveMethodCallParam;
use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v12\v0\typo3\RemoveRelativeToCurrentScriptArgumentsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../../config/config_test.php');
    $rectorConfig->rule(RemoveRelativeToCurrentScriptArgumentsRector::class);
    $rectorConfig->ruleWithConfiguration(
        RemoveMethodCallParamRector::class,
        [new RemoveMethodCallParam('TYPO3\\CMS\\Backend\\Backend\\Avatar\\Image', 'getUrl', 0)]
    );
};
