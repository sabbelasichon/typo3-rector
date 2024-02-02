<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\DataHandling;

if (class_exists('TYPO3\CMS\Core\DataHandling\PageDoktypeRegistry')) {
    return;
}

final class PageDoktypeRegistry
{
    public function add(int $dokType, array $configuration): void
    {

    }
}
