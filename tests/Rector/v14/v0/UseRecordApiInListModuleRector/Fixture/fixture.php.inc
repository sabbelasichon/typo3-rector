<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\UseRecordApiInListModuleRector\Fixture;

use TYPO3\CMS\Backend\RecordList\DatabaseRecordList;

class MyClass extends DatabaseRecordList
{
    public function foo(): void
    {
        $table = 'table';
        $rowArray = [];
        $row = [];
        $indent = 1;
        $translations = [];
        $enabled = false;
        $this->renderListRow($table, $rowArray, $indent, $translations, $enabled);
        $this->makeControl($table, $row);
        $this->makeCheckbox($table, $row);
        $this->languageFlag($table, $row);
        $this->makeLocalizationPanel($table, $row);
        $this->linkWrapItems($table, 2, 'code', $row);
        $this->getPreviewUriBuilder($table, $row);
        $this->isRecordDeletePlaceholder($row);
        $this->isRowListingConditionFulfilled($table, $row);
    }

    public function skip(): void
    {
        $table = 'table';
        $rowArray = null;
        $row = null;
        $indent = 1;
        $translations = [];
        $enabled = false;
        $this->renderListRow($table, $rowArray, $indent, $translations, $enabled);
        $this->makeControl($table, $row);
        $this->makeCheckbox($table, $row);
        $this->languageFlag($table, $row);
        $this->makeLocalizationPanel($table, $row);
        $this->linkWrapItems($table, 2, 'code', $row);
        $this->getPreviewUriBuilder($table, $row);
        $this->isRecordDeletePlaceholder($row);
        $this->isRowListingConditionFulfilled($table, $row);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\UseRecordApiInListModuleRector\Fixture;

use TYPO3\CMS\Backend\RecordList\DatabaseRecordList;

class MyClass extends DatabaseRecordList
{
    public function foo(): void
    {
        $table = 'table';
        $rowArray = [];
        $row = [];
        $indent = 1;
        $translations = [];
        $enabled = false;
        $record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $rowArray);
        $this->renderListRow($table, $record, $indent, $translations, $enabled);
        $record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
        $this->makeControl($table, $record);
        $record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
        $this->makeCheckbox($table, $record);
        $record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
        $this->languageFlag($table, $record);
        $record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
        $this->makeLocalizationPanel($table, $record);
        $record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
        $this->linkWrapItems($table, 2, 'code', $record);
        $record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
        $this->getPreviewUriBuilder($table, $record);
        $record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
        $this->isRecordDeletePlaceholder($record);
        $record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
        $this->isRowListingConditionFulfilled($record);
    }

    public function skip(): void
    {
        $table = 'table';
        $rowArray = null;
        $row = null;
        $indent = 1;
        $translations = [];
        $enabled = false;
        $this->renderListRow($table, $rowArray, $indent, $translations, $enabled);
        $this->makeControl($table, $row);
        $this->makeCheckbox($table, $row);
        $this->languageFlag($table, $row);
        $this->makeLocalizationPanel($table, $row);
        $this->linkWrapItems($table, 2, 'code', $row);
        $this->getPreviewUriBuilder($table, $row);
        $this->isRecordDeletePlaceholder($row);
        $this->isRowListingConditionFulfilled($table, $row);
    }
}

?>
