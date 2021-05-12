<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Cache\Frontend;

if (interface_exists(FrontendInterface::class)) {
    return;
}

interface FrontendInterface
{
    public function set($entryIdentifier, $data, array $tags = [], $lifetime = null);

    public function get($entryIdentifier);
}
