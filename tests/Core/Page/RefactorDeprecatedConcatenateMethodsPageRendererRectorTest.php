<?php

namespace Ssch\TYPO3Rector\Tests\Core\Page;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

class RefactorDeprecatedConcatenateMethodsPageRendererRectorTest extends AbstractRectorWithConfigTestCase
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
        yield [__DIR__ . '/Fixture/refactor_deprecated_concatenate_methods.php.inc'];
    }
}
