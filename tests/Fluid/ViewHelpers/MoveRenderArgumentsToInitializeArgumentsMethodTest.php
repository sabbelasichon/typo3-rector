<?php

namespace Ssch\TYPO3Rector\Tests\Fluid\ViewHelpers;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Fluid\ViewHelpers\MoveRenderArgumentsToInitializeArgumentsMethod;

class MoveRenderArgumentsToInitializeArgumentsMethodTest extends AbstractRectorTestCase
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

    protected function getRectorsWithConfiguration(): array
    {
        return [
            MoveRenderArgumentsToInitializeArgumentsMethod::class => [],
        ];
    }
}
