<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Backend\Controller;

use Iterator;
use Ssch\TYPO3Rector\Rector\Backend\Controller\RemovePropertiesFromSimpleDataHandlerControllerRector;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

final class RemovePropertiesFromSimpleDataHandlerControllerRectorTest extends AbstractRectorWithConfigTestCase
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

    protected function getRectorClass(): string
    {
        return RemovePropertiesFromSimpleDataHandlerControllerRector::class;
    }
}
