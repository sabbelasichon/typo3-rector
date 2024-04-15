<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->import(__DIR__ . '/../../../../../../config/v13/strict-types.php');
    $rectorConfig->phpVersion(PhpVersionFeature::TYPED_PROPERTIES);
};
