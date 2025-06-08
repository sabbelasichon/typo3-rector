<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Rector\ValueObject\PhpVersionFeature;
use Ssch\TYPO3Rector\General\Renaming\ConstantsToBackedEnumRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->phpVersion(PhpVersionFeature::ENUM);
    $rectorConfig->ruleWithConfiguration(ConstantsToBackedEnumRector::class, [
        new RenameClassAndConstFetch(
            'TYPO3\CMS\Core\Imaging\Icon',
            'SIZE_DEFAULT',
            'TYPO3\CMS\Core\Imaging\IconSize',
            'DEFAULT'
        ),
        new RenameClassAndConstFetch(
            'TYPO3\CMS\Core\Imaging\Icon',
            'SIZE_SMALL',
            'TYPO3\CMS\Core\Imaging\IconSize',
            'SMALL'
        ),
        new RenameClassAndConstFetch(
            'TYPO3\CMS\Core\Imaging\Icon',
            'SIZE_MEDIUM',
            'TYPO3\CMS\Core\Imaging\IconSize',
            'MEDIUM'
        ),
        new RenameClassAndConstFetch(
            'TYPO3\CMS\Core\Imaging\Icon',
            'SIZE_LARGE',
            'TYPO3\CMS\Core\Imaging\IconSize',
            'LARGE'
        ),
        new RenameClassAndConstFetch(
            'TYPO3\CMS\Core\Imaging\Icon',
            'SIZE_MEGA',
            'TYPO3\CMS\Core\Imaging\IconSize',
            'MEGA'
        ),
    ]);
};
