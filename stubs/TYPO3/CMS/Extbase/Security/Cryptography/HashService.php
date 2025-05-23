<?php

namespace TYPO3\CMS\Extbase\Security\Cryptography;

if(class_exists('TYPO3\CMS\Extbase\Security\Cryptography\HashService')) {
    return;
}

final class HashService
{
    public function generateHmac(string $string): string
    {

    }

    public function appendHmac(string $string): string
    {

    }

    public function validateHmac(string $string, string $hmac): bool
    {

    }

    public function validateAndStripHmac(string $string): string
    {

    }
}
