<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersionFeature;
use Ssch\TYPO3Rector\CodeQuality\General\AddAutoconfigureAttributeToClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->symfonyContainerXml(__DIR__ . '/../xml/services.xml');
    $rectorConfig->phpVersion(PhpVersionFeature::ATTRIBUTES);
    $rectorConfig->rule(AddAutoconfigureAttributeToClassRector::class);
};
