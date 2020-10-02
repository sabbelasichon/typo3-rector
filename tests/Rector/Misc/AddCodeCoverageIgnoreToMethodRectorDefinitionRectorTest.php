<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Misc;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\Misc\AddCodeCoverageIgnoreToMethodRectorDefinitionRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class AddCodeCoverageIgnoreToMethodRectorDefinitionRectorTest extends AbstractRectorTestCase
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
        return AddCodeCoverageIgnoreToMethodRectorDefinitionRector::class;
    }
}
