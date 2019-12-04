<?php

namespace Ssch\TYPO3Rector\Tests\IndexedSearch\Controller;

use Iterator;
use Ssch\TYPO3Rector\Rector\IndexedSearch\Controller\RenamePiListBrowserResultsRector;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

class RenamePiListBrowserResultsRectorTest extends AbstractRectorWithConfigTestCase
{
    /**
     * @dataProvider provideDataForTest()
     *
     * @param string $file
     */
    public function test(string $file): void
    {
        $this->doTestFile($file);
    }

    public function provideDataForTest(): Iterator
    {
        yield [__DIR__ . '/Fixtures/rename_pi_list_browser_results_method.php.inc'];
    }

    protected function getRectorsWithConfiguration(): array
    {
        return [
            RenamePiListBrowserResultsRector::class => [],
        ];
    }
}
