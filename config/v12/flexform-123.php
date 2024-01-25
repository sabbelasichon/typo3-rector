<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v12\v3\flexform\MigrateItemsToIndexedArrayKeysForFlexFormItemsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->services()
        ->set(MigrateItemsToIndexedArrayKeysForFlexFormItemsRector::class)->tag('typo3_rector.flexform_rectors');
};
