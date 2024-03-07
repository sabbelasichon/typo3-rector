<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Ssch\TYPO3Rector\General\Renaming\ConstantsToBackedEnumValueRector;
use Ssch\TYPO3Rector\General\Renaming\RenameAttributeRector;
use Ssch\TYPO3Rector\General\Renaming\ValueObject\RenameAttribute;
use Ssch\TYPO3Rector\TYPO312\v0\IgnorePageTypeRestrictionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->import(__DIR__ . '/strict-types.php');
    $rectorConfig->import(__DIR__ . '/tca-130.php');
    $rectorConfig->import(__DIR__ . '/introduce-capabilities-bit-set.php');
    $rectorConfig->import(__DIR__ . '/typo3-130-extbase-hash-service-core-hash-service.php');
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
        new RenameClassAndConstFetch(
            'TYPO3\CMS\Core\Authentication\LoginType',
            'LOGIN',
            'TYPO3\CMS\Core\Authentication\LoginType',
            'LOGIN'
        ),
        new RenameClassAndConstFetch(
            'TYPO3\CMS\Core\Authentication\LoginType',
            'LOGOUT',
            'TYPO3\CMS\Core\Authentication\LoginType',
            'LOGOUT'
        ),
    ]);
    $rectorConfig->ruleWithConfiguration(RenameAttributeRector::class, [
        new RenameAttribute('TYPO3\CMS\Backend\Attribute\Controller', 'TYPO3\CMS\Backend\Attribute\AsController'),
    ]);
    $rectorConfig->rule(\Ssch\TYPO3Rector\TYPO313\v0\EventListenerConfigurationToAttributeRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\TYPO313\v0\AddMethodGetAllPageNumbersToPaginationInterfaceRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\TYPO313\v0\ChangeSignatureForLastInsertIdRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\TYPO313\v0\ChangeSignatureOfConnectionQuoteRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\TYPO313\v0\MigrateExtbaseHashServiceToUseCoreHashServiceRector::class);
    $rectorConfig->singleton(\Ssch\TYPO3Rector\TYPO313\v0\StrictTypesPersistenceManagerRector::class);
    $rectorConfig->singleton(\Ssch\TYPO3Rector\TYPO313\v0\UseStrictTypesInExtbaseActionControllerRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\TYPO313\v0\IntroduceCapabilitiesBitSetRector::class);
    $rectorConfig->rule(\Ssch\TYPO3Rector\TYPO313\v0\SubstituteItemFormElIDRector::class);
};
