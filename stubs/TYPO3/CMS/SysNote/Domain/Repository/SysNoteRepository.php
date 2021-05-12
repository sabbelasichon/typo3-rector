<?php


namespace TYPO3\CMS\SysNote\Domain\Repository;

if (class_exists(SysNoteRepository::class)) {
    return;
}

class SysNoteRepository
{
    public function findByPidsAndAuthorId($pids, int $author, int $position = null): array
    {
        return [];
    }
}
