<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Cache;

use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;

if (class_exists('TYPO3\CMS\Core\Cache\CacheManager')) {
    return;
}

class CacheManager
{
    public function getCache(string $identifier): FrontendInterface
    {
        return new class implements FrontendInterface {

            public function set($entryIdentifier, $data, array $tags = [], $lifetime = null): void
            {

            }

            public function get($entryIdentifier)
            {
                return $entryIdentifier;
            }
        };
    }

    public function flushCachesInGroup($group): void
    {
    }
}
