<?php
declare(strict_types=1);


namespace TYPO3\CMS\Extbase\Persistence;

use TYPO3\CMS\Extbase\Persistence\Generic\Query;

if (class_exists('TYPO3\CMS\Extbase\Persistence\Repository')) {
    return;
}

class Repository
{
    public function createQuery(): Query
    {
        return new Query();
    }

    public function findByUid(int $uid): ?object
    {

    }
}
