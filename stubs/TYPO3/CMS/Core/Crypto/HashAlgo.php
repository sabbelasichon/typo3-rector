<?php

namespace TYPO3\CMS\Core\Crypto;

if (class_exists('TYPO3\CMS\Core\Crypto\HashAlgo') || enum_exists('TYPO3\CMS\Core\Crypto\HashAlgo')) {
    return;
}

class HashAlgo
{
    public const SHA3_256 = 'sha3-256';
}
