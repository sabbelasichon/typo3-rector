<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Rector\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\General\Renaming\ConstantsToBackedEnumValueRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig->phpVersion(PhpVersion::PHP_81);
    $rectorConfig->ruleWithConfiguration(ConstantsToBackedEnumValueRector::class, [
        new RenameClassAndConstFetch(
            'TYPO3\CMS\Core\Resource\AbstractFile',
            'FILETYPE_UNKNOWN',
            'TYPO3\CMS\Core\Resource\FileType',
            'UNKNOWN'
        ),
        new RenameClassAndConstFetch(
            'TYPO3\CMS\Core\Resource\AbstractFile',
            'FILETYPE_TEXT',
            'TYPO3\CMS\Core\Resource\FileType',
            'TEXT'
        ),
        new RenameClassAndConstFetch(
            'TYPO3\CMS\Core\Resource\AbstractFile',
            'FILETYPE_IMAGE',
            'TYPO3\CMS\Core\Resource\FileType',
            'IMAGE'
        ),
        new RenameClassAndConstFetch(
            'TYPO3\CMS\Core\Resource\AbstractFile',
            'FILETYPE_AUDIO',
            'TYPO3\CMS\Core\Resource\FileType',
            'AUDIO'
        ),
        new RenameClassAndConstFetch(
            'TYPO3\CMS\Core\Resource\AbstractFile',
            'FILETYPE_VIDEO',
            'TYPO3\CMS\Core\Resource\FileType',
            'VIDEO'
        ),
        new RenameClassAndConstFetch(
            'TYPO3\CMS\Core\Resource\AbstractFile',
            'FILETYPE_APPLICATION',
            'TYPO3\CMS\Core\Resource\FileType',
            'APPLICATION'
        ),

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
