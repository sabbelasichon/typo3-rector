<?php

namespace TYPO3\CMS\Core\Cache\Frontend;

if (interface_exists('TYPO3\CMS\Core\Cache\Frontend\FrontendInterface')) {
    return;
}

interface FrontendInterface
{
    public function getIdentifier();

    public function getBackend();

    public function set($entryIdentifier, $data, array $tags = [], $lifetime = null);

    public function get($entryIdentifier);

    public function has($entryIdentifier);

    public function remove($entryIdentifier);

    public function flush();

    public function flushByTag($tag);

    public function flushByTags(array $tags);

    public function collectGarbage();

    public function isValidEntryIdentifier($identifier);

    public function isValidTag($tag);
}
