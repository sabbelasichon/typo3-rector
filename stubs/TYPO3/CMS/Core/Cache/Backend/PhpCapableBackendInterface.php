<?php

namespace TYPO3\CMS\Core\Cache\Backend;

if (interface_exists('TYPO3\CMS\Core\Cache\Backend\PhpCapableBackendInterface')) {
    return;
}

interface PhpCapableBackendInterface
{
    public function requireOnce($entryIdentifier);
}
