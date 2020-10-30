<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Frontend\ContentObject;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v8\v7\RefactorRemovedMarkerMethodsFromContentObjectRendererRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RefactorRemovedMarkerMethodsFromContentObjectRendererRectorTest extends AbstractRectorTestCase
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
        yield [new SmartFileInfo(__DIR__ . '/Fixture/refactor_contentobjectrenderer_marker_methods.php.inc')];
    }

    protected function getRectorClass(): string
    {
        return RefactorRemovedMarkerMethodsFromContentObjectRendererRector::class;
    }
}
