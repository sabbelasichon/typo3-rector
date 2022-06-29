<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\ValueObject;

final class Typo3RectorRecipe
{
    /**
     * @readonly
     */
    private Typo3Version $typo3Version;

    /**
     * @readonly
     */
    private Url $url;

    /**
     * @readonly
     */
    private Name $name;

    /**
     * @readonly
     */
    private Description $description;

    /**
     * @readonly
     */
    private string $type;

    public function __construct(
        Typo3Version $typo3Version,
        Url $url,
        Name $name,
        Description $description,
        string $type
    ) {
        $this->typo3Version = $typo3Version;
        $this->url = $url;
        $this->name = $name;
        $this->description = $description;
        $this->type = $type;
    }

    public function getChangelogUrl(): string
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
            __DIR__ . '/../../../../config/%s/%s-%d.php',
            $this->getMajorVersion(),
            $this->type,
            $this->typo3Version->getFullVersion()
        );
    }
}
