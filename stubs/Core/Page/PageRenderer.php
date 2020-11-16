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

    public function addMetaTag($meta): void
    {

    }

    public function setMetaTag(string $type, string $name, string $content, array $subProperties = [], $replace = true): void
    {

    }

    public function addCssFile($file, $rel = 'stylesheet', $media = 'all', $title = '', $compress = true, $forceOnTop = false, $allWrap = '', $excludeFromConcatenation = false, $splitChar = '|', $inline = false): void
    {

    }

    public function addJsFile($file, $type = 'text/javascript', $compress = true, $forceOnTop = false, $allWrap = '', $excludeFromConcatenation = false, $splitChar = '|', $async = false, $integrity = '', $defer = false, $crossorigin = ''): void
    {

    }
}
