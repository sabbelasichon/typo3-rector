<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Core\Utility;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v9\v0\RefactorDeprecationLogRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RefactorDeprecationLogRectorTest extends AbstractRectorTestCase
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
        yield [new SmartFileInfo(__DIR__ . '/Fixture/deprecation_log.php.inc')];
    }

    protected function getRectorClass(): string
    {
        return RefactorDeprecationLogRector::class;
    }
}
