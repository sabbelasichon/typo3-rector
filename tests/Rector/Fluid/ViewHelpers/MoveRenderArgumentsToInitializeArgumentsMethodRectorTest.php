<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Fluid\ViewHelpers;

use Iterator;
use Ssch\TYPO3Rector\Rector\Fluid\ViewHelpers\MoveRenderArgumentsToInitializeArgumentsMethodRector;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class MoveRenderArgumentsToInitializeArgumentsMethodRectorTest extends AbstractRectorWithConfigTestCase
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
        yield [new SmartFileInfo(__DIR__ . '/Fixture/my_viewhelper.php.inc')];
    }

    protected function getRectorClass(): string
    {
        return MoveRenderArgumentsToInitializeArgumentsMethodRector::class;
    }
}
