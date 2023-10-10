<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\ValueObject;

final class Url
{
    /**
     * @readonly
     */
    private string $url;

    private function __construct(string $url)
    {
        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public static function createFromString(string $url): self
    {
        return new self($url);
    }
}
