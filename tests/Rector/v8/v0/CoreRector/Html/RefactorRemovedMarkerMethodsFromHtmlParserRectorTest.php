<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v0\CoreRector\Html;

use Iterator;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class RefactorRemovedMarkerMethodsFromHtmlParserRectorTest extends AbstractRectorTestCase
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
        yield [__DIR__ . '/Fixture/refactor_removed_marker_methods_from_html_parser.php.inc'];
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
