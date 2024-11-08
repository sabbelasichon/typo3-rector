<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Filesystem;

final class FilesFinder
{
    public function isExtLocalConf(string $filePath): bool
    {
        return $this->fileEqualsName($filePath, 'ext_localconf.php');
    }

    public function isExtTables(string $filePath): bool
    {
        return $this->fileEqualsName($filePath, 'ext_tables.php');
    }

    public function isInTCAOverridesFolder(string $filePath): bool
    {
        return preg_match('#Configuration/TCA/Overrides/#', $filePath) === 1;
    }

    private function fileEqualsName(string $filePath, string $fileName): bool
    {
        return basename($filePath) === $fileName;
    }
}
