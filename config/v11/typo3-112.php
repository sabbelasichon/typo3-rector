<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(
        \Ssch\TYPO3Rector\Rector\v11\v2\typo3\SubstituteEnvironmentServiceWithApplicationTypeRector::class
    );
};
