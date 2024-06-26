<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();
    // this will not import root namespace classes, like \DateTime or \Exception
    $rectorConfig->importShortClasses(false);
    $rectorConfig->import(__DIR__ . '/config.php');
};
