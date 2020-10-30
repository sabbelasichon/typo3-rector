<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Backend\Domain\Repository\Localization;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v9\v3\RemoveColPosParameterRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class LocalizationRepositoryTest extends AbstractRectorTestCase
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
        return RemoveColPosParameterRector::class;
    }
}
