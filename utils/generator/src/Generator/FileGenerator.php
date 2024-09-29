<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\Generator;

use Ssch\TYPO3Rector\Generator\Factory\TemplateFactory;
use Ssch\TYPO3Rector\Generator\FileSystem\TemplateFileSystem;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

final class FileGenerator
{
    /**
     * @readonly
     */
    private Filesystem $filesystem;

    /**
     * @readonly
     */
    private TemplateFactory $templateFactory;

    /**
     * @readonly
     */
    private TemplateFileSystem $templateFileSystem;

    public function __construct(
        Filesystem $filesystem,
        TemplateFactory $templateFactory,
        TemplateFileSystem $templateFileSystem
    ) {
        $this->filesystem = $filesystem;
        $this->templateFactory = $templateFactory;
        $this->templateFileSystem = $templateFileSystem;
    }

    /**
     * @param SplFileInfo[] $templateFileInfos
     * @param string[] $templateVariables
     * @return string[]
     */
    public function generateFiles(
        array $templateFileInfos,
        array $templateVariables,
        string $destinationDirectory
    ): array {
        $generatedFilePaths = [];

        foreach ($templateFileInfos as $fileInfo) {
            $generatedFilePaths[] = $this->generateFileInfoWithTemplateVariables(
                $fileInfo,
                $templateVariables,
                $destinationDirectory
            );
        }

        return $generatedFilePaths;
    }

    /**
     * @param array<string, mixed> $templateVariables
     */
    private function generateFileInfoWithTemplateVariables(
        SplFileInfo $smartFileInfo,
        array $templateVariables,
        string $targetDirectory
    ): string {
        $targetFilePath = $this->templateFileSystem->resolveDestination(
            $smartFileInfo,
            $templateVariables,
            $targetDirectory
        );

        $content = $this->templateFactory->create($smartFileInfo->getContents(), $templateVariables);

        $this->filesystem->dumpFile($targetFilePath, $content);

        return $targetFilePath;
    }
}
