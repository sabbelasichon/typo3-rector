<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Routing;

if (class_exists(PageRouter::class)) {
    return;
}

final class PageRouter
{
    public function generateUri(int $uid): string
    {
        return 'foo';
    }
}
