<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

use Rector\Core\Configuration\Option;
use Ssch\TYPO3Rector\Rector\v9\v5\RefactorTypeInternalTypeFileAndFileReferenceToFalRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $parameters->set(Option::AUTO_IMPORT_NAMES, false);
    $rectorConfig->rule(RefactorTypeInternalTypeFileAndFileReferenceToFalRector::class);
};
