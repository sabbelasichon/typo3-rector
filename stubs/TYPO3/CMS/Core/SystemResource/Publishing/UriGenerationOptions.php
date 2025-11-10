<?php

namespace TYPO3\CMS\Core\SystemResource\Publishing;

if (class_exists('TYPO3\CMS\Core\SystemResource\Publishing\UriGenerationOptions')) {
    return;
}

final class UriGenerationOptions
{
    public function __construct(
        ?string $uriPrefix = null,
        bool $absoluteUri = false,
        bool $cacheBusting = true
    ) {}
}
