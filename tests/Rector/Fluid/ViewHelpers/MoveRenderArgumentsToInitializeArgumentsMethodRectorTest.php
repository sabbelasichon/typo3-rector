<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Fluid\ViewHelpers;

use Iterator;
use Ssch\TYPO3Rector\Rector\Fluid\ViewHelpers\MoveRenderArgumentsToInitializeArgumentsMethodRector;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

final class MoveRenderArgumentsToInitializeArgumentsMethodRectorTest extends AbstractRectorWithConfigTestCase
{
    /**
     * @dataProvider provideDataForTest()
     */
    public function test(string $file): void
    {
        $this->doTestFile($file);
    }

    public function provideDataForTest(): Iterator
    {
        yield [__DIR__ . '/Fixture/my_viewhelper.php.inc'];
    }

    protected function getRectorClass(): string
    {
        return MoveRenderArgumentsToInitializeArgumentsMethodRector::class;
    }
}
