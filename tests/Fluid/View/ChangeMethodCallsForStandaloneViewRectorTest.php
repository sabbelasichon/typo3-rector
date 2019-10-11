<?php

namespace Ssch\TYPO3Rector\Tests\Fluid\View;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Fluid\View\ChangeMethodCallsForStandaloneViewRector;

class ChangeMethodCallsForStandaloneViewRectorTest extends AbstractRectorTestCase
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
        yield [__DIR__ . '/Fixture/fluid_standaloneview.php.inc'];
    }

    protected function getRectorsWithConfiguration(): array
    {
        return [
            ChangeMethodCallsForStandaloneViewRector::class => [],
        ];
    }
}
