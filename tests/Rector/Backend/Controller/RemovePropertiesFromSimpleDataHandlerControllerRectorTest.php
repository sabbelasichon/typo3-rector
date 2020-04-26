<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Backend\Controller;

use Iterator;
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
        yield [__DIR__ . '/Fixture/remove_properties_fetch_simpledatahandlercontroller.inc'];
    }
}
