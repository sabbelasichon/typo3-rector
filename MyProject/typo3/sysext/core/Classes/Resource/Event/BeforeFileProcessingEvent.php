<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\CMS\Core\Resource\Event;

use TYPO3\CMS\Core\Resource\Driver\DriverInterface;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\ProcessedFile;

/**
 * This event is fired before a file object is processed.
 *
 * Allows to add further information or enrich the file before the processing is kicking in.
 */
final class BeforeFileProcessingEvent
{
    public function __construct(
        private readonly DriverInterface $driver,
        private ProcessedFile $processedFile,
        private readonly FileInterface $file,
        private readonly string $taskType,
        private readonly array $configuration
    ) {}

    public function getProcessedFile(): ProcessedFile
    {
        return $this->processedFile;
    }

    public function setProcessedFile(ProcessedFile $processedFile): void
    {
        $this->processedFile = $processedFile;
    }

    public function getDriver(): DriverInterface
    {
        return $this->driver;
    }

    public function getFile(): FileInterface
    {
        return $this->file;
    }

    public function getTaskType(): string
    {
        return $this->taskType;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }
}
