<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

use Ssch\TYPO3Rector\Rector\v9\v0\ReplaceAnnotationRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig
        ->ruleWithConfiguration(ReplaceAnnotationRector::class, [
            'lazy' => 'TYPO3\CMS\Extbase\Annotation\ORM\Lazy',
            'cascade' => 'TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")',
            'transient' => 'TYPO3\CMS\Extbase\Annotation\ORM\Transient',
        ]);
};
