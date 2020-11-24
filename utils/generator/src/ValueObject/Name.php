<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\ValueObject;

use Webmozart\Assert\Assert;

final class Name
{
    /**
     * @var string
     */
    private $name;

    private function __construct(string $name)
    {
        Assert::notEndsWith($name, 'Rector');
        Assert::maxLength($name, 60);
        Assert::minLength($name, 5);
        $this->name = $name;
    }

    public function getName(): string
    {
        return sprintf('%sRector', $this->name);
    }

    public static function createFromString(string $name): self
    {
        return new self($name);
    }
}
