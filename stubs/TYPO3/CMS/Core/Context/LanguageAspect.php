<?php

namespace TYPO3\CMS\Core\Context;

if (class_exists('TYPO3\CMS\Core\Context\LanguageAspect')) {
    return;
}

class LanguageAspect
{
    public const OVERLAYS_OFF = 'off';
    public const OVERLAYS_MIXED = 'mixed';
    public const OVERLAYS_ON = 'on';
    public const OVERLAYS_ON_WITH_FLOATING = 'includeFloating';

    public function __construct(int $id = 0, ?int $contentId = null, string $overlayType = self::OVERLAYS_ON_WITH_FLOATING, array $fallbackChain = [])
    {
    }

    public function getId(): int
    {
        return 1;
    }

    public function getContentId(): int
    {
        return 1;
    }
}
