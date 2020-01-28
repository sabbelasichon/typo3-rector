<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Page;

if (class_exists(PageRenderer::class)) {
    return;
}

final class PageRenderer
{
    public function getConcatenateFiles(): void
    {
    }

    public function getConcatenateCss(): void
    {
    }

    public function getConcatenateJavascript(): void
    {
    }

    public function enableConcatenateFiles(): void
    {
    }

    public function disableConcatenateFiles(): void
    {
    }

    public function enableConcatenateJavascript(): void
    {
    }

    public function enableConcatenateCss(): void
    {
    }

    public function disableConcatenateJavascript(): void
    {
    }

    public function disableConcatenateCss(): void
    {
    }
}
