<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v0\CoreRector\Html;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RemoveRteHtmlParserEvalWriteFileRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return Iterator<string[]>
     */
    public function provideData(): Iterator
    {
        yield [__DIR__ . '/Fixture/remove_rte_html_parser_eval_write_file.php.inc'];
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
