<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\General\Renaming\ConstantsToBackedEnumValueRector;
use Ssch\TYPO3Rector\General\Renaming\RenameAttributeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/config.php');
    $rectorConfig->rule(ConstantsToBackedEnumValueRector::class);
    $rectorConfig->rule(RenameAttributeRector::class);
};
