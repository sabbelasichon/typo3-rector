<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v12\v3\flexform\MigrateItemsToIndexedArrayKeysForFlexFormItemsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(MigrateItemsToIndexedArrayKeysForFlexFormItemsRector::class);
};
