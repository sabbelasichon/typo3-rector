<?php

namespace TYPO3\CMS\Core\Cache\Backend;

if (class_exists('TYPO3\CMS\Core\Cache\Backend\AbstractBackend')) {
    return;
}

abstract class AbstractBackend implements BackendInterface
{
    public function __construct($context, $options = null)
    {
    }
}
