<?php

namespace TYPO3\CMS\Core\Domain\Access\RecordAccessVoter;

use TYPO3\CMS\Core\Context\Context;

class RecordAccessVoter
{
    public function accessGranted(string $table, array $record, Context $context): void
    {
    }
}
