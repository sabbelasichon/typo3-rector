<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Ssch\TYPO3Rector\Filesystem\FileInfoFactory;
use Symfony\Component\Finder\SplFileInfo;

final class FilesFinder
{
    /**
     * @var int
     */
    private const MAX_DIRECTORY_LEVELS_UP = 6;

    /**
     * @var string
     */
    private const EXT_EMCONF_FILENAME = 'ext_emconf.php';

    /**
     * @readonly
     */
    private FileInfoFactory $fileInfoFactory;

    public function __construct(FileInfoFactory $fileInfoFactory)
    {
        $this->fileInfoFactory = $fileInfoFactory;
    }

    public function findExtEmConfRelativeFromGivenFileInfo(SplFileInfo $fileInfo): ?SplFileInfo
    {
        return $this->findFileRelativeFromGivenFileInfo($fileInfo, self::EXT_EMCONF_FILENAME);
    }

    public function isExtLocalConf(string $filePath): bool
    {
        return $this->fileEqualsName($filePath, 'ext_localconf.php');
    }

    public function isExtTables(string $filePath): bool
    {
        return $this->fileEqualsName($filePath, 'ext_tables.php');
    }

    public function isExtEmconf(string $filePath): bool
    {
        return $this->fileEqualsName($filePath, self::EXT_EMCONF_FILENAME);
    }

    private function findFileRelativeFromGivenFileInfo(SplFileInfo $fileInfo, string $filename): ?SplFileInfo
    {
        // special case for tests
        if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
            return $fileInfo;
        }

        $currentDirectory = dirname($fileInfo->getRealPath());

        $smartFileInfo = $this->createSmartFileInfoIfFileExistsInCurrentDirectory($currentDirectory, $filename);

        if ($smartFileInfo !== null) {
            return $smartFileInfo;
        }

        // Test some levels up.
        $currentDirectoryLevel = 1;

        while ($currentDirectory = dirname($fileInfo->getPath(), $currentDirectoryLevel)) {
            $smartFileInfo = $this->createSmartFileInfoIfFileExistsInCurrentDirectory($currentDirectory, $filename);

            if ($smartFileInfo !== null) {
                return $smartFileInfo;
            }

            if ($currentDirectoryLevel > self::MAX_DIRECTORY_LEVELS_UP) {
                break;
            }

            ++$currentDirectoryLevel;
        }

        return null;
    }

    private function createSmartFileInfoIfFileExistsInCurrentDirectory(
        string $currentDirectory,
        string $filename
    ): ?SplFileInfo {
        $filePath = sprintf('%s/%s', $currentDirectory, $filename);

        if (is_file($filePath)) {
            return $this->fileInfoFactory->createFileInfoFromPath($filePath);
        }

        return null;
    }

    private function fileEqualsName(string $filePath, string $fileName): bool
    {
        return basename($filePath) === $fileName;
    }
}
