<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\v8\v3\RemovedTcaSelectTreeOptionsRector;
use Ssch\TYPO3Rector\Rector\v8\v3\SoftReferencesFunctionalityRemovedRector;
use Ssch\TYPO3Rector\Rector\v8\v4\RemoveOptionShowIfRteRector;
use Ssch\TYPO3Rector\Rector\v8\v4\SubstituteOldWizardIconsRector;
use Ssch\TYPO3Rector\Rector\v8\v5\MoveLanguageFilesFromLocallangToResourcesRector;
use Ssch\TYPO3Rector\Rector\v8\v5\RemoveOptionVersioningFollowPagesRector;
use Ssch\TYPO3Rector\Rector\v8\v5\RemoveSupportForTransForeignTableRector;
use Ssch\TYPO3Rector\Rector\v8\v6\AddTypeToColumnConfigRector;
use Ssch\TYPO3Rector\Rector\v8\v6\MigrateLastPiecesOfDefaultExtrasRector;
use Ssch\TYPO3Rector\Rector\v8\v6\MigrateOptionsOfTypeGroupRector;
use Ssch\TYPO3Rector\Rector\v8\v6\MigrateSelectShowIconTableRector;
use Ssch\TYPO3Rector\Rector\v8\v6\MoveRequestUpdateOptionFromControlToColumnsRector;
use Ssch\TYPO3Rector\Rector\v8\v6\RefactorTCARector;
use Ssch\TYPO3Rector\Rector\v8\v6\RemoveL10nModeNoCopyRector;
use Ssch\TYPO3Rector\Rector\v8\v6\RichtextFromDefaultExtrasToEnableRichtextRector;
use Ssch\TYPO3Rector\Rector\v8\v7\MoveForeignTypesToOverrideChildTcaRector;
use Ssch\TYPO3Rector\Rector\v8\v7\RemoveConfigMaxFromInputDateTimeFieldsRector;
use Ssch\TYPO3Rector\Rector\v8\v7\RemoveLocalizationModeKeepIfNeededRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set(RemovedTcaSelectTreeOptionsRector::class);
    $services->set(SoftReferencesFunctionalityRemovedRector::class);
    $services->set('substitute_old_wizard_icons_version_87')
        ->class(SubstituteOldWizardIconsRector::class)
        ->call(
        'configure',
        [[
            SubstituteOldWizardIconsRector::OLD_TO_NEW_FILE_LOCATIONS => [
                'add.gif' => 'actions-add',
                'link_popup.gif' => 'actions-wizard-link',
                'wizard_rte2.gif' => 'actions-wizard-rte',
                'wizard_link.gif' => 'actions-wizard-rte',
                'wizard_table.gif' => 'content-table',
                'edit2.gif' => 'actions-open',
                'list.gif' => 'actions-system-list-open',
                'wizard_forms.gif' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_forms.gif',
                'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_add.gif' => 'actions-add',
                'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_table.gif' => 'content-table',
                'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_edit.gif' => 'actions-open',
                'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_list.gif' => 'actions-system-list-open',
                'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_link.gif' => 'actions-wizard-link',
                'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_rte.gif' => 'actions-wizard-rte',
            ],
        ]]
    );
    $services->set(RemoveOptionShowIfRteRector::class);
    $services->set(RemoveOptionVersioningFollowPagesRector::class);
    $services->set(MoveLanguageFilesFromLocallangToResourcesRector::class);
    $services->set(RemoveSupportForTransForeignTableRector::class);
    $services->set(MoveRequestUpdateOptionFromControlToColumnsRector::class);
    $services->set(RichtextFromDefaultExtrasToEnableRichtextRector::class);
    $services->set(RefactorTCARector::class);
    $services->set(MigrateSelectShowIconTableRector::class);
    $services->set(RemoveL10nModeNoCopyRector::class);
    $services->set(AddTypeToColumnConfigRector::class);
    $services->set(MigrateOptionsOfTypeGroupRector::class);
    $services->set(RemoveConfigMaxFromInputDateTimeFieldsRector::class);
    $services->set(RemoveLocalizationModeKeepIfNeededRector::class);
    $services->set(MoveForeignTypesToOverrideChildTcaRector::class);
    $services->set(MigrateLastPiecesOfDefaultExtrasRector::class);
};
