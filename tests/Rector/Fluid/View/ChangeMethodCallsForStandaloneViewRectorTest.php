<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Fluid\View;

use Iterator;
use Ssch\TYPO3Rector\Rector\Fluid\View\ChangeMethodCallsForStandaloneViewRector;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

final class ChangeMethodCallsForStandaloneViewRectorTest extends AbstractRectorWithConfigTestCase
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
        yield [__DIR__ . '/Fixture/fluid_standaloneview.php.inc'];
    }

    protected function getRectorClass(): string
    {
        return ChangeMethodCallsForStandaloneViewRector::class;
    }
}
