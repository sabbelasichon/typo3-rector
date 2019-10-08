<?php

namespace Ssch\TYPO3Rector\Tests\Extbase;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Extbase\ChangeAttemptsParameterConsoleOutputRector;

class ChangeAttemptsParameterConsoleOutputRectorTest extends AbstractRectorTestCase
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
