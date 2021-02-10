<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v2\UseTypo3InformationForCopyRightNotice;

use Iterator;
use Rector\Testing\PHPUnit\AbstractCommunityRectorTestCase;
use Ssch\TYPO3Rector\Rector\v10\v2\UseTypo3InformationForCopyRightNoticeRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class UseTypo3InformationForCopyRightNoticeRectorTest extends AbstractCommunityRectorTestCase
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
        return UseTypo3InformationForCopyRightNoticeRector::class;
    }
}
