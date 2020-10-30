<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Fluid\View;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v8\v0\ChangeMethodCallsForStandaloneViewRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ChangeMethodCallsForStandaloneViewRectorTest extends AbstractRectorTestCase
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
        yield [new SmartFileInfo(__DIR__ . '/Fixture/fluid_standaloneview.php.inc')];
    }

    protected function getRectorClass(): string
    {
        return ChangeMethodCallsForStandaloneViewRector::class;
    }
}
