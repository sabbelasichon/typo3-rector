<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Backend\Utility;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v9\v3\BackendUtilityGetModuleUrlRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class BackendUtilityGetModuleUrlRectorTest extends AbstractRectorTestCase
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

    protected function getRectorClass(): string
    {
        return BackendUtilityGetModuleUrlRector::class;
    }
}
