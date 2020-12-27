<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v3\RefactorTsConfigRelatedMethods;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v9\v3\RefactorTsConfigRelatedMethodsRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RefactorTsConfigRelatedMethodsRectorTest extends AbstractRectorTestCase
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
        return RefactorTsConfigRelatedMethodsRector::class;
    }
}
