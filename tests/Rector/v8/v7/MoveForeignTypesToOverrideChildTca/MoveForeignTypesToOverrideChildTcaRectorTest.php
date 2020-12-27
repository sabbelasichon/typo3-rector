<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v7\MoveForeignTypesToOverrideChildTca;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v8\v7\MoveForeignTypesToOverrideChildTcaRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class MoveForeignTypesToOverrideChildTcaRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfoWithoutAutoload($fileInfo);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    protected function getRectorClass(): string
    {
        return MoveForeignTypesToOverrideChildTcaRector::class;
    }
}
