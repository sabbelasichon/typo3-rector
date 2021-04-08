<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Package;

if (class_exists(PackageManager::class)) {
    return;
}

final class PackageManager
{
    public function getActivePackages(): array
    {
        return [];
    }

    public function isPackageActive($key): bool
    {
        return true;
    }
}
