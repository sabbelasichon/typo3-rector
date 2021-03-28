<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Processor;

use Symplify\SmartFileSystem\SmartFileInfo;

interface ProcessorInterface
{
    public function process(SmartFileInfo $smartFileInfo): ?string;

    public function canProcess(SmartFileInfo $smartFileInfo): bool;

    /**
     * @return string[]
     */
    public function allowedFileExtensions(): array;
}
