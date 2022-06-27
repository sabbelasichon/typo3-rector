<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\Generator;

use Ssch\TYPO3Rector\Generator\FileSystem\TemplateFileSystem;
use Ssch\TYPO3Rector\Generator\TemplateFactory;
use Symfony\Component\Filesystem\Filesystem;
use Symplify\SmartFileSystem\SmartFileInfo;

final class FileGenerator
{
    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly TemplateFactory $templateFactory,
        private readonly TemplateFileSystem $templateFileSystem
    ) {
    }

    /**
     * @param SmartFileInfo[] $templateFileInfos
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
        SmartFileInfo $smartFileInfo,
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
