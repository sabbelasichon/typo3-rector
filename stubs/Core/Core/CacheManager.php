<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Core;

if (class_exists(CacheManager::class)) {
    return;
}

final class CacheManager
{
    public function flushCachesInGroup($group): void
    {
    }
}
