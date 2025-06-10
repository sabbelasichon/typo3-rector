<?php

namespace TYPO3\CMS\Core\Domain\Access\RecordAccessVoter;

use TYPO3\CMS\Core\Context\Context;

if (class_exists('TYPO3\CMS\Core\Domain\Access\RecordAccessVoter\RecordAccessVoter')) {
    return;
}

class RecordAccessVoter
{
    public function accessGranted(string $table, array $record, Context $context): void
    {
    }
}
