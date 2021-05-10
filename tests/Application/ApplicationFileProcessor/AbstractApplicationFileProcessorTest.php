<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Application\ApplicationFileProcessor;

use Rector\Core\Application\ApplicationFileProcessor;
use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\Configuration\Configuration;
use Rector\Core\ValueObjectFactory\Application\FileFactory;
use Rector\Core\ValueObjectFactory\ProcessResultFactory;
use Rector\Testing\PHPUnit\AbstractTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

abstract class AbstractApplicationFileProcessorTest extends AbstractTestCase
{
    /**
     * @var ApplicationFileProcessor
     */
    protected $applicationFileProcessor;

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var ProcessResultFactory
     */
    protected $processResultFactory;

    /**
     * @var RemovedAndAddedFilesCollector
     */
    protected $removedAndAddedFilesCollector;

    protected function setUp(): void
    {
        $this->bootFromConfigFileInfos([new SmartFileInfo($this->provideConfigFilePath())]);

        /** @var Configuration $configuration */
        $configuration = $this->getService(Configuration::class);
        $configuration->setIsDryRun(true);

        $this->applicationFileProcessor = $this->getService(ApplicationFileProcessor::class);
        $this->fileFactory = $this->getService(FileFactory::class);
        $this->processResultFactory = $this->getService(ProcessResultFactory::class);

        $this->removedAndAddedFilesCollector = $this->getService(RemovedAndAddedFilesCollector::class);
        $this->removedAndAddedFilesCollector->reset();
    }

    abstract protected function provideConfigFilePath(): string;
}
