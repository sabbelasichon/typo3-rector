<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v3\MoveLanguageFilesFromExtensionLang;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v9\v3\MoveLanguageFilesFromExtensionLangRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class MoveLanguageFilesFromExtensionLangRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideDataForTest()
     */
    public function test(SmartFileInfo $file): void
    {
        $this->doTestFileInfo($file);
    }

    public function provideDataForTest(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    protected function getRectorClass(): string
    {
        return MoveLanguageFilesFromExtensionLangRector::class;
    }
}
