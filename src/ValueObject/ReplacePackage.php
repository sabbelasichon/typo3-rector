<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ValueObject;

final class ReplacePackage
{
    public function __construct(
        private string $oldPackageName,
        private string $newPackageName
    ) {
    }

    public function getOldPackageName(): string
    {
        return $this->oldPackageName;
    }

    public function getNewPackageName(): string
    {
        return $this->newPackageName;
    }
}
