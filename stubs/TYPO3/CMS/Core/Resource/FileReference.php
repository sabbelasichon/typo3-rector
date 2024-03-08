<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Resource;

if (class_exists('TYPO3\CMS\Core\Resource\FileReference')) {
    return;
}

class FileReference implements FileInterface
{

    public function getIdentifier(): string
    {
        // TODO: Implement getIdentifier() method.
    }

    public function getName(): string
    {
        // TODO: Implement getName() method.
    }

    public function getStorage(): ResourceStorage
    {
        // TODO: Implement getStorage() method.
    }

    public function getHashedIdentifier(): string
    {
        // TODO: Implement getHashedIdentifier() method.
    }

    public function getParentFolder(): FolderInterface
    {
        // TODO: Implement getParentFolder() method.
    }
}
