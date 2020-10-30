<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Core\Utility;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v10\v2\MoveApplicationContextToEnvironmentApiRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class MoveApplicationContextToEnvironmentApiRectorTest extends AbstractRectorTestCase
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
        yield [new SmartFileInfo(__DIR__ . '/Fixture/use-environment-api.php.inc')];
    }

    protected function getRectorClass(): string
    {
        return MoveApplicationContextToEnvironmentApiRector::class;
    }
}
