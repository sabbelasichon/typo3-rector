<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Core;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v8\v1\RefactorDbConstantsRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RefactorDbConstantsRectorTest extends AbstractRectorTestCase
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
        yield [new SmartFileInfo(__DIR__ . '/Fixture/change_db_constants.php.inc')];
    }

    protected function getRectorClass(): string
    {
        return RefactorDbConstantsRector::class;
    }
}
