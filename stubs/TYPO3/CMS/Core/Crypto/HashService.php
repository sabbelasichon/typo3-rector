<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Crypto;

if(class_exists('TYPO3\CMS\Core\Crypto\HashService')) {
    return;
}

final class HashService
{
    public function hmac(string $input, string $additionalSecret): string
    {
        $secret = $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'] . $additionalSecret;
        return hash_hmac('sha1', $input, $secret);
    }

    public function appendHmac(string $string, string $additionalSecret): string
    {
        return $string . $this->hmac($string, $additionalSecret);
    }

    public function validateHmac(string $string, string $additionalSecret, string $hmac): bool
    {
        return hash_equals($this->hmac($string, $additionalSecret), $hmac);
    }

    public function validateAndStripHmac(string $string, string $additionalSecret): string
    {
        if (strlen($string) < 40) {
            throw new InvalidHashStringException('A hashed string must contain at least 40 characters, the given string was only ' . strlen($string) . ' characters long.', 1704454152);
        }
        $stringWithoutHmac = substr($string, 0, -40);
        if ($this->validateHmac($stringWithoutHmac, $additionalSecret, substr($string, -40)) !== true) {
            throw new InvalidHashStringException('The given string was not appended with a valid HMAC.', 1704454157);
        }
        return $stringWithoutHmac;
    }
}
