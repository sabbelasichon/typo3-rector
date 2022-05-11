<?php

namespace TYPO3\CMS\Backend\Routing;

use Psr\Http\Message\UriInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Http\Uri;

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

    public function buildUri(array $options = null, Context $context = null): ?UriInterface
    {
        return new Uri();
    }
}
