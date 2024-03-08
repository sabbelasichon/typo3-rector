<?php


namespace TYPO3\CMS\Core\Resource;

if (interface_exists('TYPO3\CMS\Core\Resource\FileInterface')) {
    return;
}

interface FileInterface extends ResourceInterface
{
    public function hasProperty(string $key): bool;
    public function getProperty(string $key): mixed;
}
