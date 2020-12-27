<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v0\UseNativePhpHex2binMethod;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v10\v0\UseNativePhpHex2binMethodRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class UseNativePhpHex2binMethodRectorTest extends AbstractRectorTestCase
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
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    protected function getRectorClass(): string
    {
        return UseNativePhpHex2binMethodRector::class;
    }
}
