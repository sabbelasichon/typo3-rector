<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\MigrateSelectShowIconTable;

use Iterator;
use Rector\Testing\PHPUnit\AbstractCommunityRectorTestCase;
use Ssch\TYPO3Rector\Rector\v8\v6\MigrateSelectShowIconTableRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class MigrateSelectShowIconTableRectorTest extends AbstractCommunityRectorTestCase
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
        return MigrateSelectShowIconTableRector::class;
    }
}
