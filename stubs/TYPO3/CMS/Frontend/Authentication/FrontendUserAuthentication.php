<?php

namespace TYPO3\CMS\Frontend\Authentication;

use TYPO3\CMS\Core\Authentication\AbstractUserAuthentication;

if (class_exists('TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication')) {
    return;
}

class FrontendUserAuthentication extends AbstractUserAuthentication
{
    protected bool $loginHidden = false;

    /**
     * @return mixed
     */
    public function getKey($type, $key)
    {
        return null;
    }

    /**
     * @return void
     */
    public function hideActiveLogin()
    {
        $this->user = null;
        $this->loginHidden = true;
    }
}
