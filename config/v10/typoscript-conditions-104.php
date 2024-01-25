<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Conditions\PIDupinRootlineConditionMatcher;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $services = $rectorConfig->services();
    $services->set(PIDupinRootlineConditionMatcher::class)->tag('typo3_rector.typoscript_condition_matcher');
};
