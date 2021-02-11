<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v7\v0\RemoveDivider2TabsConfiguration;

use Iterator;
use Rector\Testing\PHPUnit\AbstractCommunityRectorTestCase;

use Symplify\SmartFileSystem\SmartFileInfo;

final class RemoveDivider2TabsConfigurationRectorTest extends AbstractCommunityRectorTestCase
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

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
