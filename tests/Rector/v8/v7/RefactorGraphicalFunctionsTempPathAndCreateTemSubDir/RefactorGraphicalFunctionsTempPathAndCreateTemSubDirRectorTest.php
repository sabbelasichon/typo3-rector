<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v7\RefactorGraphicalFunctionsTempPathAndCreateTemSubDir;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RefactorGraphicalFunctionsTempPathAndCreateTemSubDirRectorTest extends AbstractRectorTestCase
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
