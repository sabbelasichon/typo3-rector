<?php
declare(strict_types=1);

namespace Ssch\TYPO3Rector\Filesystem;

use Ssch\TYPO3Rector\Generator\Exception\ShouldNotHappenException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

final class FileInfoFactory
{
    private Filesystem $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    public function createFileInfoFromPath(string $filePath): SplFileInfo
    {
        $currentWorkingDirectory = getcwd();
        if($currentWorkingDirectory === false) {
            throw new ShouldNotHappenException('Could not get current working directory');
        }

        $realPath = realpath($filePath);

        if($realPath === false) {
            throw new ShouldNotHappenException(sprintf('Could not get realpath for file "%s"', $filePath));
        }

        $relativeFilePath = rtrim($this->filesystem->makePathRelative($realPath, $currentWorkingDirectory), '/');
        $relativeDirectoryPath = dirname($relativeFilePath);

        return new SplFileInfo($filePath, $relativeDirectoryPath, $relativeFilePath);
    }
}
