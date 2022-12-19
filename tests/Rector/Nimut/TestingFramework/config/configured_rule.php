<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Set\Extension\NimutTestingFrameworkSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames(false);
    $rectorConfig->sets([NimutTestingFrameworkSetList::NIMUT_TESTING_FRAMEWORK_TO_TYPO3_TESTING_FRAMEWORK]);
};
