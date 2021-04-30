<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ValueObject;

use InvalidArgumentException;

/**
 * @see \Ssch\TYPO3Rector\Tests\ValueObject\EditorConfigConfigurationTest
 */
final class EditorConfigConfiguration
{
    /**
     * @var string
     */
    public const LINE_FEED = 'lf';

    /**
     * @var string
     */
    public const CARRIAGE_RETURN = 'cr';

    /**
     * @var string
     */
    public const CARRIAGE_RETURN_LINE_FEED = 'crlf';

    /**
     * @var string
     */
    public const TAB = 'tab';

    /**
     * @var string
     */
    public const SPACE = 'space';

    /**
     * @var array
     */
    private const ALLOWED_END_OF_LINE = [
        self::LINE_FEED => "\n",
        self::CARRIAGE_RETURN => "\r",
        self::CARRIAGE_RETURN_LINE_FEED => "\r\n",
    ];

    /**
     * @var array
     */
    private const ALLOWED_INDENT_STYLE = [self::TAB, self::SPACE];

    /**
     * @var string
     */
    private $indentStyle;

    /**
     * @var int
     */
    private $indentSize;

    /**
     * @var string
     */
    private $endOfLine;

    public function __construct(string $indentStyle, int $indentSize, string $endOfLine)
    {
        if (! array_key_exists($endOfLine, self::ALLOWED_END_OF_LINE)) {
            throw new InvalidArgumentException(sprintf(
                'The endOfLine %s is not allowed. Allowed are %s',
                $endOfLine,
                implode(',', array_keys(self::ALLOWED_END_OF_LINE))
            ));
        }

        if (! in_array($indentStyle, self::ALLOWED_INDENT_STYLE, true)) {
            throw new InvalidArgumentException(sprintf(
                'The indentStyle %s is not allowed. Allowed are %s',
                $endOfLine,
                implode(',', self::ALLOWED_INDENT_STYLE)
            ));
        }

        $this->indentStyle = $indentStyle;
        $this->indentSize = $indentSize;
        $this->endOfLine = self::ALLOWED_END_OF_LINE[$endOfLine];
    }

    public function getIndentStyle(): string
    {
        return $this->indentStyle;
    }

    public function getIndentSize(): int
    {
        return $this->indentSize;
    }

    public function getEndOfLine(): string
    {
        return $this->endOfLine;
    }

    public function getIndentStyleCharacter(): string
    {
        if ('space' === $this->indentStyle) {
            return ' ';
        }

        return "\t";
    }

    public function getIsTab(): bool
    {
        return 'tab' === $this->indentStyle;
    }
}
