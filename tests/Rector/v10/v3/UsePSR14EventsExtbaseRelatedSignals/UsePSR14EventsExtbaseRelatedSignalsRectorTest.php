<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v3\UsePSR14EventsExtbaseRelatedSignals;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v10\v3\UsePSR14EventsExtbaseRelatedSignalsRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class UsePSR14EventsExtbaseRelatedSignalsRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    protected function getRectorClass(): string
    {
        return UsePSR14EventsExtbaseRelatedSignalsRector::class;
    }
}
