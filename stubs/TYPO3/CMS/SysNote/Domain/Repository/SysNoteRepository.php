<?php

declare(strict_types=1);

namespace TYPO3\CMS\SysNote\Domain\Repository;

if (class_exists('TYPO3\CMS\SysNote\Domain\Repository\SysNoteRepository')) {
    return;
}

class SysNoteRepository
{
    public function findByPidsAndAuthorId($pids, int $author, int $position = null): array
    {
        return [];
    }
}
