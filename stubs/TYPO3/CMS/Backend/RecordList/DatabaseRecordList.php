<?php

namespace TYPO3\CMS\Backend\RecordList;

use TYPO3\CMS\Core\Domain\RecordFactory;

if (class_exists('TYPO3\CMS\Backend\RecordList\DatabaseRecordList')) {
    return;
}

class DatabaseRecordList
{
    protected RecordFactory $recordFactory;

    public function renderListRow($table, $row, int $indent, array $translations, bool $translationEnabled)
    {
    }

    protected function makeControl(string $table, $row)
    {
    }

    protected function makeCheckbox(string $table, $row)
    {
    }

    protected function languageFlag(string $table, $row)
    {
    }

    protected function makeLocalizationPanel(string $table, $row)
    {
    }

    protected function linkWrapItems(string $table, int $uid, string $code, $row)
    {
    }

    protected function getPreviewUriBuilder(string $table, $row)
    {
    }

    protected function isRecordDeletePlaceholder($row)
    {
    }

    protected function isRowListingConditionFulfilled($table, $row = null)
    {
    }
}
