<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\FileProcessor\Yaml\Form\Rector\v10\v0\EmailFinisherRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../../../../config/config_test.php');
    $rectorConfig->services()
        ->set(EmailFinisherRector::class)->tag('typo3_rector.yaml_rectors');
};
