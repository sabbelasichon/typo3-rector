<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Ssch\TYPO3Rector\General\Renaming\ConstantsToBackedEnumRector;
use Ssch\TYPO3Rector\General\Renaming\ConstantsToBackedEnumValueRector;
use Ssch\TYPO3Rector\General\Renaming\RenameAttributeRector;
use Ssch\TYPO3Rector\General\Renaming\ValueObject\RenameAttribute;
use Ssch\TYPO3Rector\TYPO313\v0\AddMethodGetAllPageNumbersToPaginationInterfaceRector;
use Ssch\TYPO3Rector\TYPO313\v0\ChangeSignatureForLastInsertIdRector;
use Ssch\TYPO3Rector\TYPO313\v0\ChangeSignatureOfConnectionQuoteRector;
use Ssch\TYPO3Rector\TYPO313\v0\ConvertVersionStateToEnumRector;
use Ssch\TYPO3Rector\TYPO313\v0\EventListenerConfigurationToAttributeRector;
use Ssch\TYPO3Rector\TYPO313\v0\IntroduceCapabilitiesBitSetRector;
use Ssch\TYPO3Rector\TYPO313\v0\MigrateAddPageTSConfigToPageTsConfigFileRector;
use Ssch\TYPO3Rector\TYPO313\v0\MigrateAddUserTSConfigToUserTsConfigFileRector;
use Ssch\TYPO3Rector\TYPO313\v0\MigrateExpressionBuilderTrimMethodSecondParameterRector;
use Ssch\TYPO3Rector\TYPO313\v0\MigrateExtbaseHashServiceToUseCoreHashServiceRector;
use Ssch\TYPO3Rector\TYPO313\v0\MigrateTypoScriptFrontendControllerFeUserMethodsRector;
use Ssch\TYPO3Rector\TYPO313\v0\MigrateTypoScriptFrontendControllerReadOnlyPropertiesRector;
use Ssch\TYPO3Rector\TYPO313\v0\MigrateTypoScriptFrontendControllerSysPageRector;
use Ssch\TYPO3Rector\TYPO313\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector;
use Ssch\TYPO3Rector\TYPO313\v0\RemoveSpecialPropertiesOfPageArraysRector;
use Ssch\TYPO3Rector\TYPO313\v0\StrictTypesPersistenceManagerRector;
use Ssch\TYPO3Rector\TYPO313\v0\SubstituteItemFormElIDRector;
use Ssch\TYPO3Rector\TYPO313\v0\UseStrictTypesInExtbaseActionControllerRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    // The following rules exist only to render the diff for the documentation. The actual logic is in THIS file!
    $rectorConfig->singleton(StrictTypesPersistenceManagerRector::class);
    $rectorConfig->singleton(UseStrictTypesInExtbaseActionControllerRector::class);

    $rectorConfig->ruleWithConfiguration(ConstantsToBackedEnumRector::class, [
        // See https://github.com/sabbelasichon/typo3-rector/issues/3698
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

    $rectorConfig->ruleWithConfiguration(ConstantsToBackedEnumValueRector::class, [
        // see https://github.com/sabbelasichon/typo3-rector/issues/3704
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

        // see https://github.com/sabbelasichon/typo3-rector/issues/3650
        // FIXME: This causes an infinite loop
        /*new RenameClassAndConstFetch(
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
        ),*/
    ]);
    $rectorConfig->ruleWithConfiguration(RenameAttributeRector::class, [
        new RenameAttribute('TYPO3\CMS\Backend\Attribute\Controller', 'TYPO3\CMS\Backend\Attribute\AsController'),
    ]);
    $rectorConfig->rule(EventListenerConfigurationToAttributeRector::class);
    $rectorConfig->rule(AddMethodGetAllPageNumbersToPaginationInterfaceRector::class);
    $rectorConfig->rule(ChangeSignatureForLastInsertIdRector::class);
    $rectorConfig->rule(ChangeSignatureOfConnectionQuoteRector::class);
    $rectorConfig->rule(MigrateExtbaseHashServiceToUseCoreHashServiceRector::class);
    $rectorConfig->rule(IntroduceCapabilitiesBitSetRector::class);
    $rectorConfig->rule(SubstituteItemFormElIDRector::class);
    $rectorConfig->rule(MigrateAddPageTSConfigToPageTsConfigFileRector::class);
    $rectorConfig->rule(MigrateAddUserTSConfigToUserTsConfigFileRector::class);
    $rectorConfig->rule(MigrateExpressionBuilderTrimMethodSecondParameterRector::class);
    $rectorConfig->rule(ConvertVersionStateToEnumRector::class);
    $rectorConfig->rule(RemoveConstantPageRepositoryDoktypeRecyclerRector::class);
    $rectorConfig->rule(RemoveSpecialPropertiesOfPageArraysRector::class);
    $rectorConfig->rule(MigrateTypoScriptFrontendControllerReadOnlyPropertiesRector::class);
    $rectorConfig->rule(MigrateTypoScriptFrontendControllerSysPageRector::class);
    $rectorConfig->rule(MigrateTypoScriptFrontendControllerFeUserMethodsRector::class);
};
