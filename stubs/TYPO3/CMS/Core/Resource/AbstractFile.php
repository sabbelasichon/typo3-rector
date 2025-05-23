<?php

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
    }

    public function getName(): string
    {
    }

    public function getStorage(): ResourceStorage
    {
    }

    public function getHashedIdentifier(): string
    {
    }

    public function getParentFolder(): FolderInterface
    {
    }
}
