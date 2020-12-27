<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v7\v0\RemoveDivider2TabsConfiguration;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;

use Ssch\TYPO3Rector\Rector\v7\v0\RemoveDivider2TabsConfigurationRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RemoveDivider2TabsConfigurationRectorTest extends AbstractRectorTestCase
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
        return RemoveDivider2TabsConfigurationRector::class;
    }
}
