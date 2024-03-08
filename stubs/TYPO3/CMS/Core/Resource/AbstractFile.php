<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Resource;

if (class_exists('TYPO3\CMS\Core\Resource\AbstractFile')) {
    return;
}

abstract class AbstractFile implements FileInterface
{
    public const FILETYPE_UNKNOWN = 0;

    public const FILETYPE_TEXT = 1;

    public const FILETYPE_IMAGE = 2;

    public const FILETYPE_AUDIO = 3;

    public const FILETYPE_VIDEO = 4;

    public const FILETYPE_APPLICATION = 5;
    public const OTHER_CONSTANT = 'foo';

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
