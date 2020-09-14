<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Extbase;

use Iterator;
use Ssch\TYPO3Rector\Rector\Extbase\ChangeAttemptsParameterConsoleOutputRector;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ChangeAttemptsParameterConsoleOutputRectorTest extends AbstractRectorWithConfigTestCase
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
        yield [new SmartFileInfo(__DIR__ . '/Fixture/console_output.php.inc')];
    }

    protected function getRectorsWithConfiguration(): array
    {
        return [
            ChangeAttemptsParameterConsoleOutputRector::class => [],
        ];
    }
}
