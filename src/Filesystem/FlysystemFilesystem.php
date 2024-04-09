<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Filesystem;

use League\Flysystem\FilesystemOperator;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;

final class FlysystemFilesystem implements FilesystemInterface
{
    /**
     * @readonly
     */
    private FilesystemOperator $filesystemOperator;

    public function __construct(FilesystemOperator $filesystemOperator)
    {
        $this->filesystemOperator = $filesystemOperator;
    }

    public function write(string $location, string $contents): void
    {
        $this->filesystemOperator->write($location, $contents);
    }

    public function fileExists(string $location): bool
    {
        return $this->filesystemOperator->fileExists($location);
    }

    public function read(string $location): string
    {
        return $this->filesystemOperator->read($location);
    }
}
