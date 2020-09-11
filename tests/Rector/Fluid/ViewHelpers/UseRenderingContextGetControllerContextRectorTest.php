<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Fluid\ViewHelpers;

use Iterator;
use Ssch\TYPO3Rector\Tests\AbstractRectorWithConfigTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class UseRenderingContextGetControllerContextRectorTest extends AbstractRectorWithConfigTestCase
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
        yield [__DIR__ . '/Fixture/use_rendering_context_get_controller_context.php.inc'];
    }
}
