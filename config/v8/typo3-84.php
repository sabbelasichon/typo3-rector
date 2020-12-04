<?php

declare(strict_types=1);

use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use function Rector\SymfonyPhpConfig\inline_value_objects;
use Ssch\TYPO3Rector\Rector\v8\v4\SubstituteOldWizardIconsRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Backend\Routing\FormResultCompiler;
use TYPO3\CMS\Saltedpasswords\Salt\SpellCheckingController;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');
    $services = $containerConfigurator->services();
    // @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.4/Deprecation-75363-DeprecateFormResultCompilerJStop.html
    $services->set(RenameMethodRector::class)->call('configure', [[
        RenameMethodRector::METHOD_CALL_RENAMES => inline_value_objects([
            new MethodCallRename(FormResultCompiler::class, 'JStop', 'addCssFiles'),
        ]),
    ]]);
    // @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.4/Deprecation-77826-RTEHtmlAreaSpellcheckerEntrypoint.html
    $services->set(RenameMethodRector::class)->call('configure', [[
        RenameMethodRector::METHOD_CALL_RENAMES => inline_value_objects([
            new MethodCallRename(SpellCheckingController::class, 'main', 'processRequest'),
        ]),
    ]]);
    $services->set(SubstituteOldWizardIconsRector::class)->call('configure', [[
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
    ]]);
};
