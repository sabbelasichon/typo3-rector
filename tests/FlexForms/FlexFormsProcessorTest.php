<?php

namespace Ssch\TYPO3Rector\Tests\FlexForms;

use Ssch\TYPO3Rector\Tests\Application\ApplicationFileProcessor\AbstractApplicationFileProcessorTest;

final class FlexFormsProcessorTest extends AbstractApplicationFileProcessorTest
{
    public function test(): void
    {
        $files = $this->fileFactory->createFromPaths([__DIR__ . '/Fixture']);
        $this->assertCount(2, $files);

        $this->applicationFileProcessor->run($files);

        $processResult = $this->processResultFactory->create($files);
        $this->assertCount(2, $processResult->getFileDiffs());
    }

    protected function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
