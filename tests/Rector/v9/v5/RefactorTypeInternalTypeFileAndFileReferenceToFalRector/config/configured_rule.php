<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v9\v5\RefactorTypeInternalTypeFileAndFileReferenceToFalRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->disableImportNames();
    $rectorConfig->rule(RefactorTypeInternalTypeFileAndFileReferenceToFalRector::class);
};
