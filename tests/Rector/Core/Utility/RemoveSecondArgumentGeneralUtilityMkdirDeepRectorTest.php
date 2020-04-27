<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Core\Utility;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\Core\Utility\RemoveSecondArgumentGeneralUtilityMkdirDeepRector;

class RemoveSecondArgumentGeneralUtilityMkdirDeepRectorTest extends AbstractRectorTestCase
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
        yield [__DIR__ . '/Fixture/remove_second_argument_mkdir_deep.php.inc'];
    }

    protected function getRectorClass(): string
    {
        return RemoveSecondArgumentGeneralUtilityMkdirDeepRector::class;
    }
}
