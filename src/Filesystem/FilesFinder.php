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

    private function fileEqualsName(string $filePath, string $fileName): bool
    {
        return basename($filePath) === $fileName;
    }
}
