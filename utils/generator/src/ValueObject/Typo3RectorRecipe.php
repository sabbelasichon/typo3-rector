<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\ValueObject;

final class Typo3RectorRecipe
{
    /**
     * @var Typo3Version
     */
    private $typo3Version;

    /**
     * @var Url
     */
    private $url;

    /**
     * @var Name
     */
    private $name;

    /**
     * @var Description
     */
    private $description;

    public function __construct(Typo3Version $typo3Version, Url $url, Name $name, Description $description)
    {
        $this->typo3Version = $typo3Version;
        $this->url = $url;
        $this->name = $name;
        $this->description = $description;
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

    public function getName(): string
    {
        return $this->name->getName();
    }
}
