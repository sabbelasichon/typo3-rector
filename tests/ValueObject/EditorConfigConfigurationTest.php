<?php

namespace Ssch\TYPO3Rector\Tests\ValueObject;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ssch\TYPO3Rector\ValueObject\EditorConfigConfiguration;

final class EditorConfigConfigurationTest extends TestCase
{
    /**
     * @dataProvider invalidIndentStyle
     */
    public function testInvalidIndentStyleThrowsException(string $indentStyle): void
    {
        $this->expectException(InvalidArgumentException::class);
        new EditorConfigConfiguration($indentStyle, 2, EditorConfigConfiguration::LINE_FEED);
    }

    /**
     * @dataProvider validIndentStyle
     */
    public function testValidIndentStyle(string $indentStyle): void
    {
        $editorConfigConfiguration = new EditorConfigConfiguration(
            $indentStyle,
            2,
            EditorConfigConfiguration::LINE_FEED
        );
        self::assertSame($editorConfigConfiguration->getIndentStyle(), $indentStyle);
    }

    /**
     * @dataProvider validEndOfLine
     */
    public function testValidEndOfLine(string $endOfLine, string $expectedEndOfLine): void
    {
        $editorConfigConfiguration = new EditorConfigConfiguration(EditorConfigConfiguration::SPACE, 2, $endOfLine);
        self::assertSame($editorConfigConfiguration->getEndOfLine(), $expectedEndOfLine);
    }

    /**
     * @dataProvider inValidEndOfLine
     */
    public function testInValidEndOfLine(string $endOfLine): void
    {
        $this->expectException(InvalidArgumentException::class);
        new EditorConfigConfiguration(EditorConfigConfiguration::SPACE, 2, $endOfLine);
    }

    public function invalidIndentStyle(): array
    {
        return [
            'foo' => ['foo'],
            'bar' => ['bar'],
            'baz' => ['baz'],
        ];
    }

    public function validIndentStyle(): array
    {
        return [
            'Tab' => [EditorConfigConfiguration::TAB],
            'Space' => [EditorConfigConfiguration::SPACE],
        ];
    }

    public function validEndOfLine(): array
    {
        return [
            'Line feed' => [EditorConfigConfiguration::LINE_FEED, "\n"],
            'Carriage return' => [EditorConfigConfiguration::CARRIAGE_RETURN, "\r"],
            'Carriage return and line feed' => [EditorConfigConfiguration::CARRIAGE_RETURN_LINE_FEED, "\r\n"],
        ];
    }

    public function inValidEndOfLine(): array
    {
        return [
            'foo' => ['foo'],
            'bar' => ['bar'],
            'baz' => ['baz'],
        ];
    }
}
