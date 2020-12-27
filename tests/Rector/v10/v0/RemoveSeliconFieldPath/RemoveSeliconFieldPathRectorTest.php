<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v0\RemoveSeliconFieldPath;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v10\v0\RemoveSeliconFieldPathRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RemoveSeliconFieldPathRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    protected function getRectorClass(): string
    {
        return RemoveSeliconFieldPathRector::class;
    }
}
