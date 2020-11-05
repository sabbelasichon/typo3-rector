<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v0\RefactorRemovedMethodsFromContentObjectRenderer;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v8\v0\RefactorRemovedMethodsFromContentObjectRendererRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RefactorRemovedMethodsFromContentObjectRendererRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideDataForTest()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    public function provideDataForTest(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    protected function getRectorClass(): string
    {
        return RefactorRemovedMethodsFromContentObjectRendererRector::class;
    }
}
