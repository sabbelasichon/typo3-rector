<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Fluid;

use Rector\ChangesReporting\ValueObjectFactory\FileDiffFactory;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Rector\Parallel\ValueObject\Bridge;
use Ssch\TYPO3Rector\Contract\FileProcessor\Fluid\Rector\FluidRectorInterface;
use Ssch\TYPO3Rector\Filesystem\FileInfoFactory;

final class FluidFileProcessor implements FileProcessorInterface
{
    /**
     * @var FluidRectorInterface[]
     * @readonly
     */
    private iterable $fluidRectors = [];

    /**
     * @readonly
     */
    private FileDiffFactory $fileDiffFactory;

    /**
     * @readonly
     */
    private FileInfoFactory $fileInfoFactory;

    /**
     * @param FluidRectorInterface[] $fluidRectors
     */
    public function __construct(iterable $fluidRectors, FileDiffFactory $fileDiffFactory, FileInfoFactory $fileInfoFactory)
    {
        $this->fluidRectors = $fluidRectors;
        $this->fileDiffFactory = $fileDiffFactory;
        $this->fileInfoFactory = $fileInfoFactory;
    }

    public function supports(File $file, Configuration $configuration): bool
    {
        if ($this->fluidRectors === []) {
            return false;
        }

        $smartFileInfo = $this->fileInfoFactory->createFileInfoFromPath($file->getFilePath());

        return in_array($smartFileInfo->getExtension(), $this->getSupportedFileExtensions(), true);
    }

    /**
     * @return array{system_errors: SystemError[], file_diffs: FileDiff[]}
     */
    public function process(File $file, Configuration $configuration): array
    {
        $systemErrorsAndFileDiffs = [
            Bridge::SYSTEM_ERRORS => [],
            Bridge::FILE_DIFFS => [],
        ];

        $oldFileContents = $file->getFileContent();

        foreach ($this->fluidRectors as $fluidRector) {
            $fluidRector->transform($file);
        }

        if ($oldFileContents !== $file->getFileContent()) {
            $fileDiff = $this->fileDiffFactory->createFileDiff($file, $oldFileContents, $file->getFileContent());
            $systemErrorsAndFileDiffs[Bridge::FILE_DIFFS][] = $fileDiff;
        }

        return $systemErrorsAndFileDiffs;
    }

    public function getSupportedFileExtensions(): array
    {
        return ['html', 'xml', 'txt'];
    }
}
