<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\ForwardResponseInsteadOfForwardMethod;

use Iterator;
use Rector\Testing\PHPUnit\AbstractCommunityRectorTestCase;
use Ssch\TYPO3Rector\Rector\v11\v0\ForwardResponseInsteadOfForwardMethodRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ForwardResponseInsteadOfForwardMethodRectorTest extends AbstractCommunityRectorTestCase
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
        return ForwardResponseInsteadOfForwardMethodRector::class;
    }
}
