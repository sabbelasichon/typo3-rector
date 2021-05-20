<?php
declare(strict_types=1);

namespace TYPO3\CMS\IndexedSearch\Domain\Repository;

if (class_exists('TYPO3\CMS\IndexedSearch\Domain\Repository\IndexSearchRepository')) {
    return;
}

class IndexSearchRepository
{
    public const WILDCARD_LEFT = 'foo';
    public const WILDCARD_RIGHT = 'foo';
}
