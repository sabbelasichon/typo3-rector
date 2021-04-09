<?php
declare(strict_types=1);


namespace TYPO3\CMS\Core\Resource;

if (class_exists(ResourceFactory::class)) {
    return;
}

final class ResourceFactory
{
    public static function getInstance(): self
    {
        return new self();
    }
}
