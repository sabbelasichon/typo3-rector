<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Frontend\Controller;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

class RemoveInitTemplateMethodCallRectorTest extends AbstractRectorWithConfigTestCase
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
        yield [__DIR__ . '/Fixture/remove_init_template_from_tsfe.php.inc'];
    }
}
