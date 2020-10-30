<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Core\Utility;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v9\v0\RemoveSecondArgumentGeneralUtilityMkdirDeepRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RemoveSecondArgumentGeneralUtilityMkdirDeepRectorTest extends AbstractRectorTestCase
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
        yield [new SmartFileInfo(__DIR__ . '/Fixture/remove_second_argument_mkdir_deep.php.inc')];
    }

    protected function getRectorClass(): string
    {
        return RemoveSecondArgumentGeneralUtilityMkdirDeepRector::class;
    }
}
