<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v12\v0\flexform\MigrateEvalIntAndDouble2ToTypeNumberFlexFormRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../../config/config_test.php');
    $rectorConfig->services()
        ->set(MigrateEvalIntAndDouble2ToTypeNumberFlexFormRector::class)->tag('typo3_rector.flexform_rectors');
};
