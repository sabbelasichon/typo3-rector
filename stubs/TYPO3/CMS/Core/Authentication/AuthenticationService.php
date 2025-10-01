<?php

namespace TYPO3\CMS\Core\Authentication;

if (class_exists('TYPO3\CMS\Core\Authentication\AuthenticationService')) {
    return;
}

class AuthenticationService
{
    public function processLoginData(array $loginData, $passwordTransmissionStrategy)
    {
    }
}
