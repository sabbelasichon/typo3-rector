<?php

namespace TYPO3\CMS\Core\History;

if (class_exists('TYPO3\CMS\Core\History\RecordHistory')) {
    return;
}

class RecordHistory
{
    public $changeLog;
    public $lastHistoryEntry;

    public function getChangeLog(): array
    {
        return [];
    }

    public function getLastHistoryEntryNumber(): int
    {
        return 1;
    }
}
