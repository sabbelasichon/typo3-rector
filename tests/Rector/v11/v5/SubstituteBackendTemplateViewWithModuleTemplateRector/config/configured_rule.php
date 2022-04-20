<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

use Rector\Core\Configuration\Option;

use Rector\Core\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\Rector\v11\v5\SubstituteBackendTemplateViewWithModuleTemplateRector;

return static function (RectorConfig $rectorConfig): void {
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_74);

    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->rule(SubstituteBackendTemplateViewWithModuleTemplateRector::class);
};
