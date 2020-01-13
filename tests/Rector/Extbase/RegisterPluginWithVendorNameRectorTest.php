<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Extbase;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

class RegisterPluginWithVendorNameRectorTest extends AbstractRectorWithConfigTestCase
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
        yield [__DIR__ . '/Fixture/register_plugin_with_vendor_name.php.inc'];
    }
}
