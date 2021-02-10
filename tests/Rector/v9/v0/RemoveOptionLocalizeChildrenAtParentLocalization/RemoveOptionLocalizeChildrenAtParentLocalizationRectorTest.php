<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v0\RemoveOptionLocalizeChildrenAtParentLocalization;

use Iterator;
use Rector\Testing\PHPUnit\AbstractCommunityRectorTestCase;
use Ssch\TYPO3Rector\Rector\v9\v0\RemoveOptionLocalizeChildrenAtParentLocalizationRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RemoveOptionLocalizeChildrenAtParentLocalizationRectorTest extends AbstractCommunityRectorTestCase
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
        return RemoveOptionLocalizeChildrenAtParentLocalizationRector::class;
    }
}
