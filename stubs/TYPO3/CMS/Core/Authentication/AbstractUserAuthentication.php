<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Authentication;

if (class_exists('TYPO3\CMS\Core\Authentication\AbstractUserAuthentication')) {
    return;
}

use TYPO3\CMS\Core\Session\UserSession;

abstract class AbstractUserAuthentication
{
    /**
     * @var array|null contains user- AND session-data from database (joined tables)
     */
    public ?array $user = null;

    /**
     * @var string
     */
    public $id;

    /**
     * @return string
     */
    public function createSessionId()
    {
        return '';
    }

    /**
     * @return UserSession
     */
    public function getSession()
    {
    }
}
