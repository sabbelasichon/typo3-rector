<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Core\Resource;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v10\v0\UseMetaDataAspectRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class UseMetaDataAspectRectorTest extends AbstractRectorTestCase
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
        yield [new SmartFileInfo(__DIR__ . '/Fixture/use_metadata_aspect.php.inc')];
    }

    protected function getRectorClass(): string
    {
        return UseMetaDataAspectRector::class;
    }
}
