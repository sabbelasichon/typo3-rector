<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v8\v4\SubstituteOldWizardIconsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config_test.php');
    $rectorConfig
        ->ruleWithConfiguration(SubstituteOldWizardIconsRector::class, [
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
        ]);
};
