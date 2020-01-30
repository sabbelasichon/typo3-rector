<?php

namespace Ssch\TYPO3Rector\Tests\Frontend\ContentObject;

use Iterator;
use Ssch\TYPO3Rector\Rector\Frontend\ContentObject\RefactorRemovedMarkerMethodsFromContentObjectRendererRector;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

class RefactorRemovedMarkerMethodsFromContentObjectRendererRectorTest extends AbstractRectorWithConfigTestCase
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
        yield [__DIR__ . '/Fixture/refactor_contentobjectrenderer_marker_methods.php.inc'];
    }
}
