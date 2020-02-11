<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Core\TimeTracker;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

class TimeTrackerGlobalsToSingletonRectorTest extends AbstractRectorWithConfigTestCase
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
        yield [__DIR__ . '/Fixture/time_tracker_direct_call.php.inc'];
    }
}
