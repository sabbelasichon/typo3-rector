<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Resources\Icons\IconsProcessor;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class IconsProcessorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
        $this->assertSame(1, $this->removedAndAddedFilesCollector->getAddedFileCount());
    }

    /**
     * @return Iterator<SmartFileInfo>
     */
    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture/my_extension/', '*.gif');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
