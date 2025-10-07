<?php

namespace TYPO3\CMS\Core\Domain;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Domain\Persistence\RecordIdentityMap;

if (class_exists('TYPO3\CMS\Core\Domain\RecordFactory')) {
    return;
}

class RecordFactory
{
    public function createResolvedRecordFromDatabaseRow(string $table, array $record, ?Context $context = null, ?RecordIdentityMap $recordIdentityMap = null): RecordInterface
    {
    }
}
