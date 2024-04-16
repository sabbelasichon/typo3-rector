<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector;

use Rector\ValueObject\Application\File;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;
use Ssch\TYPO3Rector\Filesystem\FilesFinder;

final class ComposerExtensionKeyResolver
{
    /**
     * @readonly
     */
    private FilesystemInterface $filesystem;

    /**
     * @readonly
     */
    private FilesFinder $filesFinder;

    public function __construct(FilesystemInterface $filesystem, FilesFinder $filesFinder)
    {
        $this->filesystem = $filesystem;
        $this->filesFinder = $filesFinder;
    }

    public function resolveExtensionKey(File $file): ?string
    {
        if (! $this->filesFinder->isExtLocalConf($file->getFilePath())
            && ! $this->filesFinder->isExtTables($file->getFilePath())
        ) {
            return null;
        }

        $directoryName = dirname($file->getFilePath());

        $composerJsonFile = $directoryName . '/composer.json';

        if (! $this->filesystem->fileExists($composerJsonFile)) {
            return $directoryName;
        }

        $composerJsonContent = $this->filesystem->read($composerJsonFile);

        $composerJsonContentDecoded = json_decode($composerJsonContent, true);

        return $composerJsonContentDecoded['extra']['typo3/cms']['extension-key'] ?? null;
    }
}
