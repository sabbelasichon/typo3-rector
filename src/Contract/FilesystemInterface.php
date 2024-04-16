<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Contract;

interface FilesystemInterface
{
    public function write(string $location, string $contents): void;

    public function fileExists(string $location): bool;

    public function read(string $location): string;

    public function appendToFile(string $location, string $content): void;

    public function delete(string $location): void;
}
