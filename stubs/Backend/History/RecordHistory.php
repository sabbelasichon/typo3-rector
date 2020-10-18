<?php

declare(strict_types=1);

namespace TYPO3\CMS\Backend\History;

if (class_exists(RecordHistory::class)) {
    return;
}

final class RecordHistory
{

    public $changelog;
    public $lastHistoryEntry;

    public function getHistoryEntry()
    {

    }

    public function getHistoryData()
    {

    }

    public function shouldPerformRollback()
    {
    }

    public function createChangelog()
    {

    }

    public function getElementData()
    {

    }

    public function performRollback()
    {


    }

    public function createMultipleDiff()
    {

    }

    public function setLastHistoryEntry()
    {

    }
}
