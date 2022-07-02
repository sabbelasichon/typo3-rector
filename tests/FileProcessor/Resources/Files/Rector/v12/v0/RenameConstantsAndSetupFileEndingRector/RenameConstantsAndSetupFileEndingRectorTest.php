<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\FileProcessor\Resources\Files\Rector\v12\v0\RenameConstantsAndSetupFileEndingRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RenameConstantsAndSetupFileEndingRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
        $this->assertCount(1, $this->removedAndAddedFilesCollector->getMovedFiles());
    }

    /**
     * @dataProvider provideDataSkippedFiles
     */
    public function testSkip(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
        $this->assertCount(0, $this->removedAndAddedFilesCollector->getMovedFiles());
    }

    /**
     * @return Iterator<SmartFileInfo>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture/my_extension/', '*.txt');
    }

    /**
     * @return Iterator<SmartFileInfo>
     */
    public function provideDataSkippedFiles(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture/my_other_extension/', '*.*');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
