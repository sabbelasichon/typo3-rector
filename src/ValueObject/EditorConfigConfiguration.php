<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ValueObject;

final class EditorConfigConfiguration
{
    /**
     * @var string
     */
    private $indentStyle;

    /**
     * @var int
     */
    private $indentSize;

    public function __construct(string $indentStyle, int $indentSize)
    {
        $this->indentStyle = $indentStyle;
        $this->indentSize = $indentSize;
    }

    public function getIndentStyle(): string
    {
        return $this->indentStyle;
    }

    public function getIndentSize(): int
    {
        return $this->indentSize;
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
