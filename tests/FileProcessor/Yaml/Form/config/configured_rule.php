<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

use Ssch\TYPO3Rector\FileProcessor\Yaml\Form\Rector\EmailFinisherRector;
use Ssch\TYPO3Rector\FileProcessor\Yaml\Form\Rector\TranslationFileRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../config/config_test.php');
    $rectorConfig->rule(EmailFinisherRector::class);
    $rectorConfig->rule(TranslationFileRector::class);
};
