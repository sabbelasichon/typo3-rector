<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\TYPO313\v0\EventListenerConfigurationToAttributeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config.php');
    $rectorConfig->symfonyContainerXml(__DIR__ . '/../xml/services.xml');
    $rectorConfig->phpVersion(PhpVersion::PHP_81);
    $rectorConfig->rule(EventListenerConfigurationToAttributeRector::class);
};
