<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Frontend\ContentObject;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v8\v0\RefactorRemovedMethodsFromContentObjectRendererRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RefactorRemovedMethodsFromContentObjectRendererRectorTest extends AbstractRectorTestCase
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
        yield [new SmartFileInfo(__DIR__ . '/Fixture/refactor_contentobjectrenderer_methods.php.inc')];
        yield [
            new SmartFileInfo(__DIR__ . '/Fixture/refactor_contentobjectrenderer_methods_frontend_controller.php.inc'),
        ];
    }

    protected function getRectorClass(): string
    {
        return RefactorRemovedMethodsFromContentObjectRendererRector::class;
    }
}
