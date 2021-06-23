<?php
namespace TYPO3\CMS\Frontend\Resource;

use TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException;
use TYPO3\CMS\Core\Resource\Exception\InvalidFileException;
use TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException;
use TYPO3\CMS\Core\Resource\Exception\InvalidPathException;

if (class_exists('TYPO3\CMS\Frontend\Resource\FilePathSanitizer')) {
    return;
}

class FilePathSanitizer
{
    /**
     * @param string $originalFileName
     * @return string
     */
    public function sanitize($originalFileName)
    {
        $originalFileName = (string) $originalFileName;
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
