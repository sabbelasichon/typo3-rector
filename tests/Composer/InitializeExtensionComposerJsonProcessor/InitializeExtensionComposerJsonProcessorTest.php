<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Composer\InitializeExtensionComposerJsonProcessor;

use Iterator;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class InitializeExtensionComposerJsonProcessorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo, AddedFileWithContent $expectedAddedFileWithContent): void
    {
        $this->doTestFileInfo($fileInfo);

        $addedFilesWithContent = $this->removedAndAddedFilesCollector->getAddedFilesWithContent();
        $this->assertSame($expectedAddedFileWithContent, [$addedFilesWithContent]);
    }

    /**
     * @return Iterator<SmartFileInfo|AddedFileWithContent>
     */
    public function provideData(): Iterator
    {
        $fileContents = file_get_contents(__DIR__ . '/Expected/composer.json');
        $addedFileWithContent = new AddedFileWithContent(__DIR__ . '/Expected/composer.json', $fileContents);

        yield [new SmartFileInfo(__DIR__ . '/Fixture/ext_emconf.php'), $addedFileWithContent];
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
