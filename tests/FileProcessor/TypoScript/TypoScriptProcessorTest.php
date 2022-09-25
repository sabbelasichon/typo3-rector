<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\FileProcessor\TypoScript;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class TypoScriptProcessorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
        $this->removedAndAddedFilesCollector->reset();
    }

    public function testExtbasePersistence(): void
    {
        $news = new SmartFileInfo(__DIR__ . '/Fixture/Extbase/002_extbase_persistence.txt');

        $this->doTestFile($news->getRelativeFilePath());

        $addedFilesWithContent = $this->removedAndAddedFilesCollector->getAddedFilesWithContent();
        $extbasePersistenceSmartFileInfo = new SmartFileInfo(__DIR__ . '/Expected/Extbase.php.inc');
        $this->assertSame(
            $extbasePersistenceSmartFileInfo->getContents(),
            $addedFilesWithContent[0]->getFileContent()
        );
        $this->removedAndAddedFilesCollector->reset();
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }

    /**
     * @return Iterator<array<string>>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture/TypoScript', '*.typoscript');
    }
}
