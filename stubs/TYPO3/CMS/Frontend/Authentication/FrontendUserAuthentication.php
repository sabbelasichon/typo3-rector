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
     * @param string $type
     * @param string $key
     * @return mixed
     */
    public function getKey($type, $key)
    {
        return null;
    }

    /**
     * @param string $type
     * @param string $key
     * @param mixed $data
     * @return void
     */
    public function setKey($type, $key, $data)
    {
    }

    public function storeSessionData()
    {
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
