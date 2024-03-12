<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersionFeature;
use Ssch\TYPO3Rector\General\Renaming\RenameAttributeRector;
use Ssch\TYPO3Rector\General\Renaming\ValueObject\RenameAttribute;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->phpVersion(PhpVersionFeature::ATTRIBUTES);
    $rectorConfig->ruleWithConfiguration(RenameAttributeRector::class, [
        new RenameAttribute('TYPO3\CMS\Backend\Attribute\Controller', 'TYPO3\CMS\Backend\Attribute\AsController'),
    ]);
};
