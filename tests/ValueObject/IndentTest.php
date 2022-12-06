<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\ValueObject;

use PHPUnit\Framework\TestCase;
use Rector\Core\ValueObject\Application\File;
use Ssch\TYPO3Rector\ValueObject\Indent;

final class IndentTest extends TestCase
{
    /**
     * @dataProvider provideValidFiles
     */
    public function testFromFile(string $expected, File $file): void
    {
        $indent = Indent::fromFile($file);
        self::assertSame($expected, $indent->toString());
    }

    public function testIsSpaceReturnsTrue(): void
    {
        self::assertTrue(Indent::fromFile($this->fileWithSpaces())->isSpace());
    }

    public function testLengthReturnsCorrectValue(): void
    {
        self::assertSame(2, Indent::fromFile($this->fileWithSpaces())->length());
    }

    public function testIsSpaceReturnsFalse(): void
    {
        self::assertFalse(Indent::fromFile($this->fileWithTabs())->isSpace());
    }

    /**
     * @return \Generator<array<string>>
     */
    public function provideValidStringValues(): \Generator
    {
        yield 'Tabs' => ["\t", "\t"];
        yield 'Spaces' => [' ', ' '];
    }

    /**
     * @return \Generator<array<int, File|string>>
     */
    public function provideValidFiles(): \Generator
    {
        yield 'File with tab content' => ["\t", $this->fileWithTabs()];
        yield 'File with two spaces content' => ['  ', $this->fileWithSpaces()];
    }

    public function fileWithSpaces(): File
    {
        return new File('foobar.txt', (string) file_get_contents(__DIR__ . '/Fixtures/file-with-spaces.txt'));
    }

    public function fileWithTabs(): File
    {
        return new File('foobar.txt', (string) file_get_contents(__DIR__ . '/Fixtures/tabs.txt'));
    }
}
