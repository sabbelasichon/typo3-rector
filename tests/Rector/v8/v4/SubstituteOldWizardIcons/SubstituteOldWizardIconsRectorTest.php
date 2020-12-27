<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v4\SubstituteOldWizardIcons;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v8\v4\SubstituteOldWizardIconsRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class SubstituteOldWizardIconsRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    protected function getRectorsWithConfiguration(): array
    {
        return [
            SubstituteOldWizardIconsRector::class => [
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
            ],
        ];
    }
}
