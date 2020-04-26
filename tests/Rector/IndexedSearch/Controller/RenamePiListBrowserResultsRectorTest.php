<?php

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
        yield [__DIR__ . '/Fixtures/rename_pi_list_browser_results_method.php.inc'];
    }

    protected function getRectorClass(): string
    {
        return RenamePiListBrowserResultsRector::class;
    }
}
