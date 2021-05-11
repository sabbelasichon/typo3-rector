<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Resources\Icons\IconsProcessor;

use Ssch\TYPO3Rector\Tests\Application\ApplicationFileProcessor\AbstractApplicationFileProcessorTest;

final class IconsProcessorTest extends AbstractApplicationFileProcessorTest
{
    public function test(): void
    {
        $files = $this->fileFactory->createFromPaths([__DIR__ . '/Fixture']);
        $this->applicationFileProcessor->run($files);

        $addedFilesWithContent = $this->removedAndAddedFilesCollector->getAddedFilesWithContent();

        $this->assertStringContainsString(
            'Resources/Public/Icons/Extension.gif',
            $addedFilesWithContent[0]->getFilePath()
        );
        $this->assertSame(1, $this->removedAndAddedFilesCollector->getAddedFileCount());

        $this->assertCount(1, $files);
    }

    protected function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
