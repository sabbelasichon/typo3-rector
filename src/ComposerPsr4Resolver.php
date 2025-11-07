<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector;

use Rector\ValueObject\Application\File;
use Ssch\TYPO3Rector\Contract\LocalFilesystemInterface;
use Ssch\TYPO3Rector\Filesystem\FilesFinder;

final class ComposerPsr4Resolver
{
    /**
     * @readonly
     */
    private LocalFilesystemInterface $filesystem;

    /**
     * @readonly
     */
    private FilesFinder $filesFinder;

    public function __construct(LocalFilesystemInterface $filesystem, FilesFinder $filesFinder)
    {
        $this->filesystem = $filesystem;
        $this->filesFinder = $filesFinder;
    }

    public function resolve(File $file): ?string
    {
        $filePath = $file->getFilePath();
        if (! $this->filesFinder->isExtLocalConf($filePath)
            && ! $this->filesFinder->isInTCAOverridesFolder($filePath)
        ) {
            return null;
        }

        $directoryName = $this->filesFinder->isInTCAOverridesFolder($filePath)
            ? dirname($filePath, 4)
            : dirname($filePath);

        $composerJsonFile = $directoryName . '/composer.json';

        if (! $this->filesystem->fileExists($composerJsonFile)) {
            return null;
        }

        $composerJsonContent = $this->filesystem->read($composerJsonFile);

        $composerJsonContentDecoded = json_decode($composerJsonContent, true, 512, JSON_THROW_ON_ERROR);

        $autoLoadings = $composerJsonContentDecoded['autoload']['psr-4'] ?? [];
        foreach ($autoLoadings as $namespace => $path) {
            if (str_starts_with($path, 'Classes')) {
                return $namespace;
            }
        }

        return null;
    }
}
