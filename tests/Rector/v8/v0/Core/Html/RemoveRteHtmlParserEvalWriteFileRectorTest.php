<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v0\Core\Html;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Rector\v8\v0\RemoveRteHtmlParserEvalWriteFileRector;
use Symplify\SmartFileSystem\SmartFileInfo;

final class RemoveRteHtmlParserEvalWriteFileRectorTest extends AbstractRectorTestCase
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
        yield [new SmartFileInfo(__DIR__ . '/Fixture/remove_rte_html_parser_eval_write_file.php.inc')];
    }

    protected function getRectorClass(): string
    {
        return RemoveRteHtmlParserEvalWriteFileRector::class;
    }
}
