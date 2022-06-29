<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\FileProcessor\Resources\Icons\IconsFileProcessor;
use Ssch\TYPO3Rector\FileProcessor\Resources\Icons\Rector\v8\v3\IconsRector;
use Ssch\TYPO3Rector\Rector\v8\v3\RefactorMethodFileContentRector;
use Ssch\TYPO3Rector\Rector\v8\v3\RefactorQueryViewTableWrapRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->rule(RefactorMethodFileContentRector::class);
    $rectorConfig->rule(RefactorQueryViewTableWrapRector::class);

    $services = $rectorConfig->services();
    $services->set(IconsFileProcessor::class)->autowire();
    $services->set(IconsRector::class);
};
