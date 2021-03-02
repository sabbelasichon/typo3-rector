<?php

namespace Ssch\TYPO3Rector\ComposerPackages\Tests\Rector\RemovePackageVersionsRector;

use Iterator;
use Rector\Testing\PHPUnit\AbstractCommunityRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RemovePackageVersionsRectorTest extends AbstractCommunityRectorTestCase
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

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
