<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Extbase;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v9\v5\RemoveInternalAnnotationRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RemoveInternalAnnotationRectorTest extends AbstractRectorTestCase
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
        yield [new SmartFileInfo(__DIR__ . '/Fixture/remove_internal_annotation.php.inc')];
        yield [new SmartFileInfo(__DIR__ . '/Fixture/keep_internal_annotation.php.inc')];
    }

    protected function getRectorClass(): string
    {
        return RemoveInternalAnnotationRector::class;
    }
}
