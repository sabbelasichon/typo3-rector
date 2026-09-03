<?php

namespace TYPO3\CMS\Core\Attribute;

if (class_exists('TYPO3\CMS\Core\Attribute\AsNonSchedulableCommand')) {
    return;
}

#[\Attribute(\Attribute::TARGET_CLASS)]
class AsNonSchedulableCommand
{
    public function __construct(
    ) {}
}
