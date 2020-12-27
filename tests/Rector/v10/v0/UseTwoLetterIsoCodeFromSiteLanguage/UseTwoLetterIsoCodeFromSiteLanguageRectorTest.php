<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v0\UseTwoLetterIsoCodeFromSiteLanguage;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v10\v0\UseTwoLetterIsoCodeFromSiteLanguageRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class UseTwoLetterIsoCodeFromSiteLanguageRectorTest extends AbstractRectorTestCase
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
        return UseTwoLetterIsoCodeFromSiteLanguageRector::class;
    }
}
