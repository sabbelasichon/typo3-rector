<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ValueObject;

final class TypoScriptToPhpFile
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $content;

    public function __construct(string $filename, string $content)
    {
        $this->filename = $filename;
        $this->content = $content;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
