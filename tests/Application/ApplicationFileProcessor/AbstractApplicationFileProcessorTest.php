<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Application\ApplicationFileProcessor;

use Rector\Core\Application\ApplicationFileProcessor;
use Rector\Core\Configuration\Configuration;
use Rector\Core\HttpKernel\RectorKernel;
use Rector\Core\ValueObjectFactory\Application\FileFactory;
use Rector\Core\ValueObjectFactory\ProcessResultFactory;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

abstract class AbstractApplicationFileProcessorTest extends AbstractKernelTestCase
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

    protected function setUp(): void
    {
        $this->bootKernelWithConfigs(RectorKernel::class, [$this->provideConfigFilePath()]);

        /** @var Configuration $configuration */
        $configuration = $this->getService(Configuration::class);
        $configuration->setIsDryRun(true);

        $this->applicationFileProcessor = $this->getService(ApplicationFileProcessor::class);
        $this->fileFactory = $this->getService(FileFactory::class);
        $this->processResultFactory = $this->getService(ProcessResultFactory::class);
    }

    abstract protected function provideConfigFilePath(): string;
}
