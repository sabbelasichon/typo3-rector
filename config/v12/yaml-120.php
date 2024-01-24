<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v12\v0\yaml\RemoveElementTceFormsYamlRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->services()
        ->set(RemoveElementTceFormsYamlRector::class)->tag('typo3_rector.yaml_rectors');
};
