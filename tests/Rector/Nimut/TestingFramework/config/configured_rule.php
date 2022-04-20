<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Set\Typo3SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->disableImportNames();
    $rectorConfig->sets([Typo3SetList::NIMUT_TESTING_FRAMEWORK_TO_TYPO3_TESTING_FRAMEWORK]);
};
