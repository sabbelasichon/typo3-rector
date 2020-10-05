<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Backend\Utility;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class BackendUtilityGetModuleUrlRectorTest extends AbstractRectorWithConfigTestCase
{
    /**
     * @dataProvider provideDataForTest()
     */
    public function test(SmartFileInfo $file): void
    {
        $this->doTestFileInfo($file);
    }

    public function provideDataForTest(): Iterator
    {
        yield [new SmartFileInfo(__DIR__ . '/Fixture/backend_utility_get_module_url.php.inc')];
        yield [new SmartFileInfo(__DIR__ . '/Fixture/backend_utility_get_module_url_no_second_param.php.inc')];
    }
}
