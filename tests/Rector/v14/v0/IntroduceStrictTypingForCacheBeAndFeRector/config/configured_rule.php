<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\TYPO314\v0\IntroduceStrictTypingForCacheBeAndFeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->import(__DIR__ . '/../../../../../../config/v14/strict-types.php');
    $rectorConfig->rule(IntroduceStrictTypingForCacheBeAndFeRector::class);
};
