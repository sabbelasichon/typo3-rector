<?php

namespace Ssch\TYPO3Rector\Tests\Frontend\Page;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

class CallEnableFieldsFromPageRepositoryRectorTest extends AbstractRectorWithConfigTestCase
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
        yield [__DIR__ . '/Fixture/call_enable_fields_from_page_repository.php.inc'];
    }
}
