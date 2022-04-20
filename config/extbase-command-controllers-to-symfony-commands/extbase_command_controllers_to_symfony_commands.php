<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v9\v5\ExtbaseCommandControllerToSymfonyCommand\AddArgumentToSymfonyCommandRector;
use Ssch\TYPO3Rector\Rector\v9\v5\ExtbaseCommandControllerToSymfonyCommand\AddCommandsToReturnRector;
use Ssch\TYPO3Rector\Rector\v9\v5\ExtbaseCommandControllerToSymfonyCommandRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->rule(AddArgumentToSymfonyCommandRector::class);
    $rectorConfig->rule(AddCommandsToReturnRector::class);
    $rectorConfig->rule(ExtbaseCommandControllerToSymfonyCommandRector::class);
};
