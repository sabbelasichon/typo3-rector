<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Core;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v9\v0\CheckForExtensionInfoRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class CheckForExtensionInfoRectorTest extends AbstractRectorTestCase
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
        yield [new SmartFileInfo(__DIR__ . '/Fixture/check_for_info_instead_of_infopagetsconfig.php.inc')];
    }

    protected function getRectorClass(): string
    {
        return CheckForExtensionInfoRector::class;
    }
}
