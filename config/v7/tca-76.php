<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\v7\v0\RemoveDivider2TabsConfigurationRector;
use Ssch\TYPO3Rector\Rector\v7\v4\DropAdditionalPaletteRector;
use Ssch\TYPO3Rector\Rector\v7\v4\MoveLanguageFilesFromRemovedCmsExtensionRector;
use Ssch\TYPO3Rector\Rector\v7\v5\RemoveIconsInOptionTagsRector;
use Ssch\TYPO3Rector\Rector\v7\v5\UseExtPrefixForTcaIconFileRector;
use Ssch\TYPO3Rector\Rector\v7\v6\AddRenderTypeToSelectFieldRector;
use Ssch\TYPO3Rector\Rector\v7\v6\MigrateT3editorWizardToRenderTypeT3editorRector;
use Ssch\TYPO3Rector\Rector\v7\v6\RemoveIconOptionForRenderTypeSelectRector;
use Ssch\TYPO3Rector\Rector\v8\v4\SubstituteOldWizardIconsRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set(RemoveDivider2TabsConfigurationRector::class);
    $services->set(MoveLanguageFilesFromRemovedCmsExtensionRector::class);
    $services->set(DropAdditionalPaletteRector::class);
    $services->set(RemoveIconsInOptionTagsRector::class);
    $services->set(UseExtPrefixForTcaIconFileRector::class);
    $services->set(MigrateT3editorWizardToRenderTypeT3editorRector::class);
    $services->set(SubstituteOldWizardIconsRector::class)
        ->call(
            'configure',
            [[
                SubstituteOldWizardIconsRector::OLD_TO_NEW_FILE_LOCATIONS => [
                    'add.gif' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_add.gif',
                    'link_popup.gif' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_link.gif',
                    'wizard_rte2.gif' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_rte.gif',
                    'wizard_table.gif' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_table.gif',
                    'edit2.gif' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_edit.gif',
                    'list.gif' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_list.gif',
                    'wizard_forms.gif' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_forms.gif',
                ],
            ]]
        );
    $services->set(AddRenderTypeToSelectFieldRector::class);
    $services->set(RemoveIconOptionForRenderTypeSelectRector::class);
};
