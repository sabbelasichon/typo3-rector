<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Crypto;

if(class_exists('TYPO3\CMS\Core\Crypto\HashService')) {
    return;
}

final class HashService
{
    public function hmac(string $input, string $additionalSecret): void
    {
    }

    public function appendHmac(string $string, string $additionalSecret): void
    {
    }

    public function validateHmac(string $string, string $additionalSecret, string $hmac): void
    {
    }

    public function validateAndStripHmac(string $string, string $additionalSecret): void
    {

    }
}
