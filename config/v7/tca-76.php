<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v7\v0\RemoveDivider2TabsConfigurationRector;
use Ssch\TYPO3Rector\Rector\v7\v4\DropAdditionalPaletteRector;
use Ssch\TYPO3Rector\Rector\v7\v4\MoveLanguageFilesFromRemovedCmsExtensionRector;
use Ssch\TYPO3Rector\Rector\v7\v5\RemoveIconsInOptionTagsRector;
use Ssch\TYPO3Rector\Rector\v7\v5\UseExtPrefixForTcaIconFileRector;
use Ssch\TYPO3Rector\Rector\v7\v6\AddRenderTypeToSelectFieldRector;
use Ssch\TYPO3Rector\Rector\v7\v6\MigrateT3editorWizardToRenderTypeT3editorRector;
use Ssch\TYPO3Rector\Rector\v7\v6\RemoveIconOptionForRenderTypeSelectRector;
use Ssch\TYPO3Rector\Rector\v8\v4\SubstituteOldWizardIconsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(RemoveDivider2TabsConfigurationRector::class);
    $rectorConfig->rule(MoveLanguageFilesFromRemovedCmsExtensionRector::class);
    $rectorConfig->rule(DropAdditionalPaletteRector::class);
    $rectorConfig->rule(RemoveIconsInOptionTagsRector::class);
    $rectorConfig->rule(UseExtPrefixForTcaIconFileRector::class);
    $rectorConfig->rule(MigrateT3editorWizardToRenderTypeT3editorRector::class);
    $rectorConfig
        ->ruleWithConfiguration(SubstituteOldWizardIconsRector::class, [
            'add.gif' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_add.gif',
            'link_popup.gif' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_link.gif',
            'wizard_rte2.gif' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_rte.gif',
            'wizard_table.gif' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_table.gif',
            'edit2.gif' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_edit.gif',
            'list.gif' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_list.gif',
            'wizard_forms.gif' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_forms.gif',
        ]);
    $rectorConfig->rule(AddRenderTypeToSelectFieldRector::class);
    $rectorConfig->rule(RemoveIconOptionForRenderTypeSelectRector::class);
};
