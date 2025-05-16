<?php

namespace TYPO3Fluid\Fluid\View;

if (class_exists('TYPO3Fluid\Fluid\View\TemplatePaths')) {
    return;
}

class TemplatePaths
{
    public const DEFAULT_TEMPLATES_DIRECTORY = 'Resources/Private/Templates/';
    public const DEFAULT_LAYOUTS_DIRECTORY = 'Resources/Private/Layouts/';
    public const DEFAULT_PARTIALS_DIRECTORY = 'Resources/Private/Partials/';
    public const DEFAULT_FORMAT = 'html';
    public const CONFIG_TEMPLATEROOTPATHS = 'templateRootPaths';
    public const CONFIG_LAYOUTROOTPATHS = 'layoutRootPaths';
    public const CONFIG_PARTIALROOTPATHS = 'partialRootPaths';
    public const CONFIG_FORMAT = 'format';
    public const NAME_TEMPLATES = 'templates';
    public const NAME_LAYOUTS = 'layouts';
    public const NAME_PARTIALS = 'partials';

    public function setTemplatePathAndFilename(string $templatePathAndFilename): void
    {
    }

    public function setLayoutPathAndFilename(string $layoutPathAndFilename): void
    {
    }

    public function getTemplateRootPaths(): array
    {
        return [];
    }

    public function setTemplateRootPaths(array $templateRootPaths): void
    {
    }

    public function getLayoutRootPaths(): array
    {
        return [];
    }

    public function setLayoutRootPaths(array $layoutRootPaths): void
    {
    }

    public function getPartialRootPaths(): array
    {
        return [];
    }

    public function setPartialRootPaths(array $partialRootPaths): void
    {
    }

    public function getFormat(): string
    {
        return '';
    }

    public function setFormat(string $format): void
    {
    }

    public function resolveTemplateFileForControllerAndActionAndFormat(string $controller, string $action, ?string $format = null): ?string
    {
        return null;
    }

    public function resolveAvailableTemplateFiles(?string $controllerName, ?string $format = null): array
    {
        return [];
    }

    public function resolveAvailablePartialFiles(?string $format = null): array
    {
        return [];
    }

    public function resolveAvailableLayoutFiles(?string $format = null): array
    {
        return [];
    }

    protected function resolveFilesInFolders(array $folders, string $format): array
    {
        return [];
    }

    protected function resolveFilesInFolder(string $folder, string $format): array
    {
        return [];
    }

    public function fillFromConfigurationArray(array $paths): void
    {
    }

    public function fillDefaultsByPackageName(string $packageName): void
    {
    }

    public function getLayoutIdentifier(string $layoutName = 'Default'): string
    {
        return '';
    }

    public function getLayoutSource(string $layoutName = 'Default'): string
    {
        return '';
    }

    public function getTemplateIdentifier(?string $controller = 'Default', ?string $action = 'Default'): string
    {
        return '';
    }

    public function setTemplateSource($source): void
    {
    }

    /**
     * @return string
     */
    public function getTemplateSource(?string $controller = 'Default', ?string $action = 'Default')
    {
        return '';
    }

    public function getLayoutPathAndFilename(string $layoutName = 'Default'): string
    {
        return '';
    }

    public function getPartialIdentifier(string $partialName): string
    {
        return '';
    }

    public function getPartialSource(string $partialName): string
    {
        return '';
    }

    public function getPartialPathAndFilename(string $partialName): string
    {
        return '';
    }
}
