<?php

namespace TYPO3\CMS\Core\Cache\Backend;

use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;

if (interface_exists('TYPO3\CMS\Core\Cache\Backend\BackendInterface')) {
    return;
}

interface BackendInterface
{
    public function setCache(FrontendInterface $cache);
    public function set($entryIdentifier, $data, array $tags = [], $lifetime = null);
    public function get($entryIdentifier);
    public function has($entryIdentifier);
    public function remove($entryIdentifier);
    public function flush();
    public function collectGarbage();
}
