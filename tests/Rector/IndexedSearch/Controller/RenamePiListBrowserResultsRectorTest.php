<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\IndexedSearch\Controller;

use Iterator;
use Ssch\TYPO3Rector\Rector\IndexedSearch\Controller\RenamePiListBrowserResultsRector;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RenamePiListBrowserResultsRectorTest extends AbstractRectorWithConfigTestCase
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
        return RenamePiListBrowserResultsRector::class;
    }
}
