<?php
namespace TYPO3\CMS\Frontend\Authentication;

if (class_exists('TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication')) {
    return;
}

class FrontendUserAuthentication
{
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
