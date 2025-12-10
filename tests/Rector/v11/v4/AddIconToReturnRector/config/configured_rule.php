<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO311\v4\AddIconToReturnRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->ruleWithConfiguration(AddIconToReturnRector::class, [
        AddIconToReturnRector::IDENTIFIER => 'my-icon',
        AddIconToReturnRector::OPTIONS => [
            'provider' => \stdClass::class,
            'source' => 'mysvg.svg',
        ],
    ]);
};
