<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Configuration;

if (class_exists(Features::class)) {
    return;
}

final class Features
{
    public function isFeatureEnabled(string $feature): bool
    {
        return true;
    }
}
