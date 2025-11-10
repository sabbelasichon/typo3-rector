<?php

namespace TYPO3\CMS\Core\SystemResource;

use TYPO3\CMS\Core\SystemResource\Type\PublicResourceInterface;

if (class_exists('TYPO3\CMS\Core\SystemResource\SystemResourceFactory')) {
    return;
}

class SystemResourceFactory
{
    public function createPublicResource(string $resourceString): PublicResourceInterface
    {
    }
}
