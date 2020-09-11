<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Backend\Controller;

use Iterator;
use Ssch\TYPO3Rector\Rector\Backend\Controller\RemovePropertiesFromSimpleDataHandlerControllerRector;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RemovePropertiesFromSimpleDataHandlerControllerRectorTest extends AbstractRectorWithConfigTestCase
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

    protected function getRectorClass(): string
    {
        return RemovePropertiesFromSimpleDataHandlerControllerRector::class;
    }
}
