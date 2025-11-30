<?php

namespace TYPO3\CMS\Extbase\Attribute;

use TYPO3\CMS\Core\Resource\Enum\DuplicationBehavior;

if (class_exists('TYPO3\CMS\Extbase\Attribute\FileUpload')) {
    return;
}

class FileUpload
{
    public function __construct(
        array $validation,
        string $uploadFolder = '',
        bool $addRandomSuffix = true,
        bool $createUploadFolderIfNotExist = true,
        DuplicationBehavior $duplicationBehavior = DuplicationBehavior::REPLACE
    ) {
    }
}
