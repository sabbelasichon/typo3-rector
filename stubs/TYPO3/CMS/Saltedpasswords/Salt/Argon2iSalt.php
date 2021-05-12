<?php


namespace TYPO3\CMS\Saltedpasswords\Salt;

if (class_exists(Argon2iSalt::class)) {
    return;
}

class Argon2iSalt
{
}
