<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\FileProcessor\Resources\Icons;

use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Rector\Parallel\ValueObject\Bridge;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Ssch\TYPO3Rector\Contract\FileProcessor\Resources\IconRectorInterface;
use Ssch\TYPO3Rector\Filesystem\FileInfoFactory;
use Ssch\TYPO3Rector\Helper\FilesFinder;
use Symfony\Component\Filesystem\Filesystem;

final class IconsFileProcessor implements FileProcessorInterface
{
    /**
     * @var string
     */
    private const EXT_ICON_NAME = 'ext_icon';

    /**
     * @readonly
     */
    private FilesFinder $filesFinder;

    /**
     * @var IconRectorInterface[]
     * @readonly
     */
    private array $iconsRector = [];

    /**
     * @readonly
     */
    private Filesystem $filesystem;

    /**
     * @readonly
     */
    private FileInfoFactory $fileInfoFactory;

    /**
     * @param IconRectorInterface[] $iconsRector
     */
    public function __construct(FilesFinder $filesFinder, Filesystem $filesystem, array $iconsRector, FileInfoFactory $fileInfoFactory)
    {
        $this->filesFinder = $filesFinder;
        $this->iconsRector = $iconsRector;
        $this->filesystem = $filesystem;
        $this->fileInfoFactory = $fileInfoFactory;
    }

    /**
     * @return array{system_errors: SystemError[], file_diffs: FileDiff[]}
     */
    public function process(File $file, Configuration $configuration): array
    {
        foreach ($this->iconsRector as $iconRector) {
            $iconRector->refactorFile($file);
        }

        // to keep parent contract with return values
        return [
            Bridge::SYSTEM_ERRORS => [],
            Bridge::FILE_DIFFS => [],
        ];
    }

    public function supports(File $file, Configuration $configuration): bool
    {
        $smartFileInfo = $this->fileInfoFactory->createFileInfoFromPath($file->getFilePath());

        if ($this->shouldSkip($smartFileInfo->getFilenameWithoutExtension())) {
            return false;
        }

        $extEmConfSmartFileInfo = $this->filesFinder->findExtEmConfRelativeFromGivenFileInfo($smartFileInfo);

        if ($extEmConfSmartFileInfo === null) {
            return false;
        }

        return ! $this->filesystem->exists($this->createIconPath($file));
    }

    public function getSupportedFileExtensions(): array
    {
        return ['png', 'gif', 'svg'];
    }

    private function createIconPath(File $file): string
    {
        $smartFileInfo = $this->fileInfoFactory->createFileInfoFromPath($file->getFilePath());

        $realPath = dirname($smartFileInfo->getRealPath());
        $relativeTargetFilePath = sprintf('/Resources/Public/Icons/Extension.%s', $smartFileInfo->getExtension());

        return $realPath . $relativeTargetFilePath;
    }

    private function shouldSkip(string $filenameWithoutExtension): bool
    {
        if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
            return false;
        }

        return $filenameWithoutExtension !== self::EXT_ICON_NAME;
    }
}
