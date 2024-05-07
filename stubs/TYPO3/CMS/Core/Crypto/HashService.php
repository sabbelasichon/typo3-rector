<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Crypto;

if (class_exists('TYPO3\CMS\Core\Crypto\HashService')) {
    return;
}

final class HashService
{
    public function hmac(string $input, string $additionalSecret): string
    {
        return '';
    }

    public function appendHmac(string $string, string $additionalSecret): string
    {
        return '';
    }

    public function validateHmac(string $string, string $additionalSecret, string $hmac): bool
    {
        return false;
    }

    public function validateAndStripHmac(string $string, string $additionalSecret): string
    {
        return '';
    }
}
