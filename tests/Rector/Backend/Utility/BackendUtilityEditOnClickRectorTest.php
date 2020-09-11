<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Backend\Utility;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class BackendUtilityEditOnClickRectorTest extends AbstractRectorWithConfigTestCase
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
        yield [__DIR__ . '/Fixture/backend_utility_edit_on_click.php.inc'];
    }
}
