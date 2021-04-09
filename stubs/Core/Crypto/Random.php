<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Crypto;

if (class_exists(Random::class)) {
    return;
}

final class Random
{
    public function generateRandomBytes(): string
    {
        return 'bytes';
    }

    public function generateRandomHexString(): string
    {
        return 'hex';
    }
}
