<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\TypoScript;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class TypoScriptProcessorTest extends AbstractRectorTestCase
{
    public function test(): void
    {
        $files = $this->fileFactory->createFromPaths([__DIR__ . '/Fixture']);
        $this->assertCount(6, $files);

        $this->applicationFileProcessor->run($files);

        $processResult = $this->processResultFactory->create($files);

        $addedFilesWithContent = $this->removedAndAddedFilesCollector->getAddedFilesWithContent();
        $extbasePersistenceSmartFileInfo = new SmartFileInfo(__DIR__ . '/Expected/Extbase.php.inc');
        $this->assertSame(
            $extbasePersistenceSmartFileInfo->getContents(),
            $addedFilesWithContent[0]->getFileContent()
        );

        $this->assertCount(4, $processResult->getFileDiffs());
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
