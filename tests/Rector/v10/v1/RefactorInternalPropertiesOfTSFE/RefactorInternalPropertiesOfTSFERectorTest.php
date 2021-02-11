<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v1\RefactorInternalPropertiesOfTSFE;

use Iterator;
use Rector\Testing\PHPUnit\AbstractCommunityRectorTestCase;
use Ssch\TYPO3Rector\Rector\v10\v1\RefactorInternalPropertiesOfTSFERector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RefactorInternalPropertiesOfTSFERectorTest extends \Rector\Testing\PHPUnit\AbstractCommunityRectorTestCase
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
