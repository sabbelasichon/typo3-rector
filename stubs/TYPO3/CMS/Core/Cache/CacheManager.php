<?php
namespace TYPO3\CMS\Core\Cache;

use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;

if (class_exists('TYPO3\CMS\Core\Cache\CacheManager')) {
    return;
}

class CacheManager
{
    /**
     * @param string $identifier
     * @return \TYPO3\CMS\Core\Cache\Frontend\FrontendInterface
     */
    public function getCache($identifier)
    {
        $identifier = (string) $identifier;
        return new Anonymous__80f9d48e45a850436cae4f188819f43c__0();
    }

    /**
     * @return void
     */
    public function flushCachesInGroup($group)
    {
    }
}
class Anonymous__80f9d48e45a850436cae4f188819f43c__0 implements FrontendInterface
{
    public function set($entryIdentifier, $data, array $tags = [], $lifetime = null): void
    {

    }
    public function get($entryIdentifier)
    {
        return $entryIdentifier;
    }
}
