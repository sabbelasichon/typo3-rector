<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Core\DataHandling;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

final class DataHandlerRmCommaRectorTest extends AbstractRectorWithConfigTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
}
