<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v0\RefactorRemovedMethodsFromGeneralUtility;

use Iterator;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RefactorRemovedMethodsFromGeneralUtilityRectorTest extends \Rector\Testing\PHPUnit\AbstractCommunityRectorTestCase
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
