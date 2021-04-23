<?php
declare(strict_types=1);


namespace TYPO3\CMS\Extbase\Service;

if (class_exists(TypeHandlingService::class)) {
    return;
}

class TypeHandlingService
{
    public function parseType($type): void
    {
    }

    public function normalizeType($type): void
    {
    }

    public function isLiteral($type): void
    {
    }

    public function isSimpleType($type): void
    {
    }
}
