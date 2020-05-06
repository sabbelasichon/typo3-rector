<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\IndexedSearch\Controller;

use Iterator;
use Ssch\TYPO3Rector\Rector\IndexedSearch\Controller\RenamePiListBrowserResultsRector;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

final class RenamePiListBrowserResultsRectorTest extends AbstractRectorWithConfigTestCase
{
    /**
     * @dataProvider provideDataForTest()
     */
    public function test(string $file): void
    {
        $this->doTestFile($file);
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
