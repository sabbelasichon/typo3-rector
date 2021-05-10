<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Composer\InitializeExtensionComposerJsonProcessor;

use Ssch\TYPO3Rector\Tests\Application\ApplicationFileProcessor\AbstractApplicationFileProcessorTest;
use Symplify\SmartFileSystem\SmartFileInfo;

final class InitializeExtensionComposerJsonProcessorTest extends AbstractApplicationFileProcessorTest
{
    public function test(): void
    {
        $files = $this->fileFactory->createFromPaths([__DIR__ . '/Fixture']);
        $this->assertCount(1, $files);

        $this->applicationFileProcessor->run($files);

        $addedFilesWithContent = $this->removedAndAddedFilesCollector->getAddedFilesWithContent();
        $composerJsonSmartFileInfo = new SmartFileInfo(__DIR__ . '/Expected/composer.json');
        $this->assertSame($composerJsonSmartFileInfo->getContents(), $addedFilesWithContent[0]->getFileContent());
    }

    protected function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
