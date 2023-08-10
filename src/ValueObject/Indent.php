<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ValueObject;

use Rector\Core\ValueObject\Application\File;
use Webmozart\Assert\Assert;

/**
 * @see https://github.com/ergebnis/json-normalizer/blob/main/src/Format/Indent.php
 * @see \Ssch\TYPO3Rector\Tests\ValueObject\IndentTest
 */
final class Indent
{
    /**
     * @var array<string, string>
     */
    public const CHARACTERS = [
        'space' => ' ',
        'tab' => "\t",
    ];

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromFile(File $file): self
    {
        if (\preg_match('#^(?P<indent>( +|\t+)).*#m', $file->getFileContent(), $match) === 1) {
            return self::fromString($match['indent']);
        }

        return self::fromSizeAndStyle(4, 'space');
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function isSpace(): bool
    {
        return \preg_match('#^( +).*#', $this->value) === 1;
    }

    public function length(): int
    {
        return strlen($this->value);
    }

    private static function fromSizeAndStyle(int $size, string $style): self
    {
        Assert::greaterThanEq($size, 1);
        Assert::keyExists(self::CHARACTERS, $style);

        $value = \str_repeat(self::CHARACTERS[$style], $size);

        return new self($value);
    }

    private static function fromString(string $value): self
    {
        Assert::regex($value, '/^( *|\t+)$/');

        return new self($value);
    }
}
