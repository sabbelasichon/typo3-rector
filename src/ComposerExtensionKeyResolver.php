<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector;

use Rector\ValueObject\Application\File;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;

final class ComposerExtensionKeyResolver
{
    /**
     * @readonly
     */
    private FilesystemInterface $filesystem;

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function resolveExtensionKey(File $file): ?string
    {
        $directoryName = dirname($file->getFilePath());
        $composerJsonFile = $directoryName . '/composer.json';

        if (! $this->filesystem->fileExists($composerJsonFile)) {
            return null;
        }

        $composerJsonContent = $this->filesystem->read($composerJsonFile);

        $composerJsonContentDecoded = json_decode($composerJsonContent, true);

        return $composerJsonContentDecoded['extra']['typo3/cms']['extension-key'] ?? null;
    }
}
