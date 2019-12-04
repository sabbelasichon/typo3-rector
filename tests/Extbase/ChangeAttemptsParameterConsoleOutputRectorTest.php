<?php

namespace Ssch\TYPO3Rector\Tests\Extbase;

use Iterator;
use Ssch\TYPO3Rector\Rector\Extbase\ChangeAttemptsParameterConsoleOutputRector;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

class ChangeAttemptsParameterConsoleOutputRectorTest extends AbstractRectorWithConfigTestCase
{
    /**
     * @dataProvider provideDataForTest()
     *
     * @param string $file
     */
    public function test(string $file): void
    {
        $this->doTestFile($file);
    }

    public function provideDataForTest(): Iterator
    {
        yield [__DIR__ . '/Fixture/console_output.php.inc'];
    }

    protected function getRectorsWithConfiguration(): array
    {
        return [
            ChangeAttemptsParameterConsoleOutputRector::class => [],
        ];
    }
}
