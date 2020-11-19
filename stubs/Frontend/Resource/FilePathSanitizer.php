<?php
declare(strict_types=1);

namespace TYPO3\CMS\Frontend\Resource;

use TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException;
use TYPO3\CMS\Core\Resource\Exception\InvalidFileException;
use TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException;
use TYPO3\CMS\Core\Resource\Exception\InvalidPathException;

if (class_exists(FilePathSanitizer::class)) {
    return;
}

final class FilePathSanitizer
{
    public function sanitize(string $originalFileName): string
    {
        if ($originalFileName === 'foo') {
            throw new InvalidFileNameException($originalFileName);
        }

        if ($originalFileName === 'bar') {
            throw new InvalidPathException($originalFileName);
        }

        if ($originalFileName === 'baz') {
            throw new FileDoesNotExistException($originalFileName);
        }

        if ($originalFileName === 'bazbar') {
            throw new InvalidFileException($originalFileName);
        }

        return 'foo';
    }
}
