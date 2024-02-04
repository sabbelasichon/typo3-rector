<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\TYPO312\v4\CommandConfigurationToAnnotationRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config.php');
    $rectorConfig->symfonyContainerXml(__DIR__ . '/../xml/services.xml');
    $rectorConfig->phpVersion(PhpVersion::PHP_81);
    $rectorConfig->rule(CommandConfigurationToAnnotationRector::class);
};
