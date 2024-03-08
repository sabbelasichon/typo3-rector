<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Resource;

if (interface_exists('TYPO3\CMS\Core\Resource\ResourceInterface')) {
    return;
}

interface ResourceInterface
{
    public function getIdentifier(): string;
    public function getName(): string;
    public function getStorage(): ResourceStorage;
    public function getHashedIdentifier(): string;
    public function getParentFolder(): FolderInterface;
}
