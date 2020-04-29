<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Frontend\ContentObject;

use Iterator;
use Ssch\TYPO3Rector\Rector\Frontend\ContentObject\RefactorRemovedMethodsFromContentObjectRendererRector;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;

final class RefactorRemovedMethodsFromContentObjectRendererRectorTest extends AbstractRectorWithConfigTestCase
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
        yield [__DIR__ . '/Fixture/refactor_contentobjectrenderer_methods.php.inc'];
        yield [__DIR__ . '/Fixture/refactor_contentobjectrenderer_methods_frontend_controller.php.inc'];
    }

    protected function getRectorClass(): string
    {
        return RefactorRemovedMethodsFromContentObjectRendererRector::class;
    }
}
