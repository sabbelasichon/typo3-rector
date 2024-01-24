<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\FileProcessor\FlexForms\Rector\v7\v6\RenderTypeFlexFormRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../../../config/config_test.php');
    $rectorConfig->services()
        ->set(RenderTypeFlexFormRector::class)->tag('typo3_rector.flexform_rectors');
};
