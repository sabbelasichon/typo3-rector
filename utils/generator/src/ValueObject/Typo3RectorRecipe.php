<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\ValueObject;

final class Typo3RectorRecipe
{
    public function __construct(
        private Typo3Version $typo3Version,
        private Url $url,
        private Name $name,
        private Description $description
    ) {
    }

    public function getUrlToRstFile(): string
    {
        return $this->url->getUrl();
    }

    public function getMajorVersion(): string
    {
        return sprintf('v%d', $this->typo3Version->getMajor());
    }

    public function getMinorVersion(): string
    {
        return sprintf('v%d', $this->typo3Version->getMinor());
    }

    public function getDescription(): string
    {
        return $this->description->getDescription();
    }

    public function getRectorName(): string
    {
        return $this->name->getRectorName();
    }

    public function getTestDirectory(): string
    {
        return $this->name->getName() . 'Rector';
    }

    public function getSet(): string
    {
        return sprintf(
            __DIR__ . '/../../../../config/%s/typo3-%d.php',
            $this->getMajorVersion(),
            $this->typo3Version->getFullVersion()
        );
    }
}
