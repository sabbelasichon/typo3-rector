<?php

namespace TYPO3\CMS\Core\Cache\Backend;

if (interface_exists('TYPO3\CMS\Core\Cache\Backend\TransientBackendInterface')) {
    return;
}

interface TransientBackendInterface extends BackendInterface
{
}
