<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php71\Rector\FuncCall\RemoveExtraParametersRector;
use Ssch\TYPO3Rector\Rector\v9\v0\QueryLogicalOrAndLogicalAndToArrayParameterRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->rule(QueryLogicalOrAndLogicalAndToArrayParameterRector::class);
    $rectorConfig->rule(RemoveExtraParametersRector::class);
};
