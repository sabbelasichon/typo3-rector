<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\__Major__\__Minor__\__Name__;

use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class __Name__Test extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    public function provideData(): \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    protected function getRectorClass(): string
    {
        return \Ssch\TYPO3Rector\Tests\Rector\__Major__\__Minor__\__Name__::class;
    }
}
