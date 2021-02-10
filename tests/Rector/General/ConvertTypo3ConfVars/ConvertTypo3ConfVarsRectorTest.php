<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\General\ConvertTypo3ConfVars;

use Iterator;
use Rector\Testing\PHPUnit\AbstractCommunityRectorTestCase;
use Ssch\TYPO3Rector\Rector\General\ConvertTypo3ConfVarsRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ConvertTypo3ConfVarsRectorTest extends AbstractCommunityRectorTestCase
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
        return ConvertTypo3ConfVarsRector::class;
    }
}
