<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Filesystem;

use League\Flysystem\Filesystem;
use League\Flysystem\InMemory\InMemoryFilesystemAdapter;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;

final class FilesystemFactory
{
    private string $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public function create(): FilesystemInterface
    {
        $argv = $_SERVER['argv'] ?? [];
        $isDryRun = in_array('--dry-run', $argv, true) || in_array('-n', $argv, true);

        if ($isDryRun || StaticPHPUnitEnvironment::isPHPUnitRun()) {
            $adapter = new InMemoryFilesystemAdapter();
        } else {
            $adapter = new LocalFilesystemAdapter($this->projectDir);
        }

        return new FlysystemFilesystem(new Filesystem($adapter));
    }

    public function createLocalFilesystem(): FilesystemInterface
    {
        if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
            $adapter = new InMemoryFilesystemAdapter();
        } else {
            $adapter = new LocalFilesystemAdapter($this->projectDir);
        }

        return new FlysystemFilesystem(new Filesystem($adapter));
    }
}
