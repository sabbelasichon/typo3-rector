<?php

namespace TYPO3\CMS\Core\Cache;

if (class_exists('TYPO3\CMS\Core\Cache\CacheTag')) {
    return;
}

final class CacheTag
{
    public string $name;
    public int $lifetime = PHP_INT_MAX;

    public function __construct(string $name, int $lifetime = PHP_INT_MAX)
    {
        $this->lifetime = $lifetime;
        $this->name = $name;
    }
}
