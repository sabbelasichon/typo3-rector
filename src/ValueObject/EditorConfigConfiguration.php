<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ValueObject;

use InvalidArgumentException;

final class EditorConfigConfiguration
{
    /**
     * @var array
     */
    private const END_OF_LINE = [
        'lf' => "\n",
        'cr' => "\r",
        'crlf' => "\r\n",
    ];

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
        if (! array_key_exists($endOfLine, self::END_OF_LINE)) {
            throw new InvalidArgumentException(sprintf(
                'The endOfLine %s is not allowed. Allowed are %s',
                $endOfLine,
                implode(',', array_keys(self::END_OF_LINE))
            ));
        }

        $this->indentStyle = $indentStyle;
        $this->indentSize = $indentSize;
        $this->endOfLine = self::END_OF_LINE[$endOfLine];
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
