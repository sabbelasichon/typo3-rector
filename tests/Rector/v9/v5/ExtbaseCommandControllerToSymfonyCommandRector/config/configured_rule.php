<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

use Ssch\TYPO3Rector\Set\Typo3SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([Typo3SetList::EXTBASE_COMMAND_CONTROLLERS_TO_SYMFONY_COMMANDS]);
};
