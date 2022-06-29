<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\ValueObject;

use Stringable;
use Webmozart\Assert\Assert;

final class ComposerPackage implements Stringable
{
    /**
     * @readonly
     */
    private string $package;

    public function __construct(string $package)
    {
        [$vendor, $name] = explode('/', $package);
        Assert::stringNotEmpty($vendor);
        Assert::stringNotEmpty($name);

        $this->package = $package;
    }

    public function __toString(): string
    {
        return $this->package;
    }
}
