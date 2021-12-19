<?php

namespace TYPO3\CMS\Backend\Routing;

if (class_exists('TYPO3\CMS\Backend\Routing\PreviewUriBuilder')) {
    return;
}

class PreviewUriBuilder
{
    public const OPTION_SWITCH_FOCUS = 'switchFocus';

    public static function create(int $pageId, string $alternativeUri = null)
    {
        return new PreviewUriBuilder();
    }

    public function withRootLine(array $rootLine)
    {
        return new PreviewUriBuilder();
    }

    public function withSection(string $section)
    {
        return new PreviewUriBuilder();
    }

    public function withAdditionalQueryParameters(string $additionalQueryParameters)
    {
        return new PreviewUriBuilder();
    }

    public function buildDispatcherDataAttributes(array $options = null): ?array
    {
        return [];
    }
}
