<?php
declare(strict_types=1);


namespace TYPO3\CMS\Extbase\Persistence;

if (class_exists('TYPO3\CMS\Extbase\Persistence\Repository')) {
    return;
}

class Repository
{
    public function createQuery(): QueryInterface
    {
    }
}
