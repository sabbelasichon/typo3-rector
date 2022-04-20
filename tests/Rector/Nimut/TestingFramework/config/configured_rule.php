<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

use Rector\Core\Configuration\Option;
use Ssch\TYPO3Rector\Set\Typo3SetList;

return static function (RectorConfig $rectorConfig): void {
    $parameters->set(Option::AUTO_IMPORT_NAMES, false);
    $rectorConfig->sets([Typo3SetList::NIMUT_TESTING_FRAMEWORK_TO_TYPO3_TESTING_FRAMEWORK]);
};
