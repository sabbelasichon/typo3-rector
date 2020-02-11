<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Frontend\ContentObject;

use Iterator;
use Ssch\TYPO3Rector\Rector\Frontend\ContentObject\RefactorRemovedMethodsFromContentObjectRendererRector;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

class RefactorRemovedMethodsFromContentObjectRendererRectorTest extends AbstractRectorWithConfigTestCase
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
        yield [__DIR__ . '/Fixture/refactor_contentobjectrenderer_methods.php.inc'];
    }

    protected function getRectorsWithConfiguration(): array
    {
        return [
            RefactorRemovedMethodsFromContentObjectRendererRector::class => [],
        ];
    }
}
