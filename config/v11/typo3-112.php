<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v11\v2\SubstituteEnvironmentServiceWithApplicationTypeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(SubstituteEnvironmentServiceWithApplicationTypeRector::class);
};
