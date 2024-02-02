<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\ValueObject;

use Ssch\TYPO3Rector\Generator\Contract\Typo3RectorTypeInterface;

final class Typo3RectorRecipe
{
    /**
     * @readonly
     */
    private Typo3Version $typo3Version;

    /**
     * @readonly
     */
    private string $url;

    /**
     * @readonly
     */
    private string $name;

    /**
     * @readonly
     */
    private string $description;

    /**
     * @readonly
     */
    private Typo3RectorTypeInterface $type;

    public function __construct(
        Typo3Version $typo3Version,
        string $url,
        string $name,
        string $description,
        Typo3RectorTypeInterface $type
    ) {
        $this->typo3Version = $typo3Version;
        $this->url = $url;
        $this->name = $name;
        $this->description = $description;
        $this->type = $type;
    }

    public function getChangelogUrl(): string
    {
        return $this->url;
    }

    public function getMajorVersionPrefixed(): string
    {
        return sprintf('v%d', $this->typo3Version->getMajor());
    }

    public function getMajorVersion(): string
    {
        return (string) $this->typo3Version->getMajor();
    }

    public function getMinorVersionPrefixed(): string
    {
        return sprintf('v%d', $this->typo3Version->getMinor());
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getRectorName(): string
    {
        return $this->name . 'Rector';
    }

    public function getTestDirectory(): string
    {
        return $this->name . 'Rector';
    }

    public function getSet(): string
    {
        return sprintf(
            __DIR__ . '/../../../../config/%s/%s-%d.php',
            $this->getMajorVersionPrefixed(),
            $this->type->__toString(),
            $this->typo3Version->getFullVersion()
        );
    }

    public function getRectorClass(): string
    {
        return $this->type->getRectorClass();
    }

    public function getRectorShortClassName(): string
    {
        return $this->type->getRectorShortClassName();
    }
}
