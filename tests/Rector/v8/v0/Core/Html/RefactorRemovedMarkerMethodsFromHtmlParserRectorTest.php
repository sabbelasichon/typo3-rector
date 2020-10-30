<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v0\Core\Html;

use Iterator;
use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v8\v0\RefactorRemovedMarkerMethodsFromHtmlParserRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RefactorRemovedMarkerMethodsFromHtmlParserRectorTest extends AbstractRectorTestCase
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
        yield [new SmartFileInfo(__DIR__ . '/Fixture/refactor_removed_marker_methods_from_html_parser.php.inc')];
    }

    protected function getRectorClass(): string
    {
        return RefactorRemovedMarkerMethodsFromHtmlParserRector::class;
    }
}
