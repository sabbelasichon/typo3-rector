<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Core;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RefactorDbConstantsRectorTest extends AbstractRectorWithConfigTestCase
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
        yield [__DIR__ . '/Fixture/change_db_constants.php.inc'];
    }
}
