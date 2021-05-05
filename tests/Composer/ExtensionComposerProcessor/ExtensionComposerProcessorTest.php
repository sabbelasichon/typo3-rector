<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Composer\ExtensionComposerProcessor;

use Ssch\TYPO3Rector\Tests\Application\ApplicationFileProcessor\AbstractApplicationFileProcessorTest;

final class ExtensionComposerProcessorTest extends AbstractApplicationFileProcessorTest
{
    public function test(): void
    {
        $files = $this->fileFactory->createFromPaths([__DIR__ . '/Fixture']);
        $this->assertCount(0, $files);

        $this->applicationFileProcessor->run($files);

        $processResult = $this->processResultFactory->create($files);

        $this->assertCount(0, $processResult->getFileDiffs());
    }

    protected function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
