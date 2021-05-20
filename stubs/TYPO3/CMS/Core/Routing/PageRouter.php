<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Routing;

if (class_exists('TYPO3\CMS\Core\Routing\PageRouter')) {
    return;
}

class PageRouter
{
    public function generateUri(int $uid): string
    {
        return 'foo';
    }
}
