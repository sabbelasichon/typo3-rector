<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Authentication;

use TYPO3\CMS\Core\Type\Enumeration;

if (class_exists('TYPO3\CMS\Core\Authentication\LoginType')) {
    return;
}

class LoginType extends Enumeration
{
    public const LOGIN = 'login';
    public const LOGOUT = 'logout';
}
