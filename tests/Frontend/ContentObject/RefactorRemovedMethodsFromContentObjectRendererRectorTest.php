<?php

namespace Ssch\TYPO3Rector\Tests\Frontend\ContentObject;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Frontend\ContentObject\RefactorRemovedMethodsFromContentObjectRendererRector;

class RefactorRemovedMethodsFromContentObjectRendererRectorTest extends AbstractRectorTestCase
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
