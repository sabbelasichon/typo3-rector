<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\RefactorExplodeUrl2ArrayFromGeneralUtility;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v9\v4\RefactorExplodeUrl2ArrayFromGeneralUtilityRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RefactorExplodeUrl2ArrayFromGeneralUtilityRectorTest extends AbstractRectorTestCase
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
        return RefactorExplodeUrl2ArrayFromGeneralUtilityRector::class;
    }
}
