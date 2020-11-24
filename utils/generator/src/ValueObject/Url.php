<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\ValueObject;

use RuntimeException;

final class Url
{
    /**
     * @var string
     */
    private $url;

    private function __construct(string $url)
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new RuntimeException('Please enter a valid Url');
        }

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
