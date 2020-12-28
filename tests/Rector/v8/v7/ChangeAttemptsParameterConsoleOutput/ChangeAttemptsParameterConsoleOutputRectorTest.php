<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v7\ChangeAttemptsParameterConsoleOutput;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v8\v7\ChangeAttemptsParameterConsoleOutputRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ChangeAttemptsParameterConsoleOutputRectorTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    protected function getRectorsWithConfiguration(): array
    {
        return [
            ChangeAttemptsParameterConsoleOutputRector::class => [],
        ];
    }
}
