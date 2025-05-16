<?php

namespace TYPO3\CMS\Core\View;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3Fluid\Fluid\Core\Cache\FluidCacheInterface;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperResolver;
use TYPO3Fluid\Fluid\View\TemplatePaths;

if (class_exists('TYPO3\CMS\Core\View\FluidViewAdapter')) {
    return;
}

class FluidViewAdapter implements ViewInterface
{
    public function getRenderingContext(): RenderingContextInterface
    {
    }

    public function setRenderingContext(RenderingContextInterface $renderingContext): void
    {
    }

    public function renderSection($sectionName, array $variables = [], $ignoreUnknown = false): string
    {
    }

    public function renderPartial($partialName, $sectionName, array $variables, $ignoreUnknown = false): string
    {
    }

    public function setTemplate(string $templateName): void
    {
    }

    public function initializeRenderingContext(): void
    {
    }

    public function setCache(FluidCacheInterface $cache): void
    {
    }

    public function getTemplatePaths(): TemplatePaths
    {
    }

    public function getViewHelperResolver(): ViewHelperResolver
    {
    }

    public function setTemplatePathAndFilename(string $templatePathAndFilename): void
    {
    }

    public function setTemplateRootPaths(array $templateRootPaths): void
    {
    }

    public function getTemplateRootPaths(): array
    {
    }

    public function setPartialRootPaths(array $partialRootPaths): void
    {
    }

    public function getPartialRootPaths(): array
    {
    }

    public function getLayoutRootPaths(): array
    {
    }

    public function setLayoutRootPaths(array $layoutRootPaths): void
    {
    }

    public function setLayoutPathAndFilename(string $layoutPathAndFilename): void
    {
    }

    public function setFormat(string $format): void
    {
    }

    public function setRequest(?ServerRequestInterface $request = null): void
    {
    }

    public function setTemplateSource(string $templateSource): void
    {
    }

    public function hasTemplate(): bool
    {
    }

    public function assign(string $key, $value): ViewInterface
    {
    }

    public function assignMultiple(array $values): ViewInterface
    {
    }

    public function render(string $templateFileName = ''): string
    {
    }
}
