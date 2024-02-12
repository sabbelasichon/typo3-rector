<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\General\Renaming\ValueObject;

final class RenameAttribute
{
    private string $oldAttribute;

    private string $newAttribute;

    public function __construct(string $oldAttribute, string $newAttribute)
    {
        $this->oldAttribute = $oldAttribute;
        $this->newAttribute = $newAttribute;
    }

    public function getOldAttribute(): string
    {
        return $this->oldAttribute;
    }

    public function getNewAttribute(): string
    {
        return $this->newAttribute;
    }
}
