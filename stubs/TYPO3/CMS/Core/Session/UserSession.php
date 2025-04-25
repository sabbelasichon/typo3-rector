<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Session;

if (class_exists('TYPO3\CMS\Core\Session\UserSession')) {
    return;
}

class UserSession
{
    public function getIdentifier(): string
    {
    }
}
