<?php

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyCustomClassWithOldPropertyCalls
{
    /**
     * @var TypoScriptFrontendController
     */
    private $typoScriptFrontendController;

    public function __construct()
    {
        $this->typoScriptFrontendController = $GLOBALS['TSFE'];
    }

    public function myMethod(): void
    {
        $frontendUserIsLoggedIn = $this->typoScriptFrontendController->loginUser;
        $backendUserIsLoggedIn = $this->getTypoScriptFrontendController()->beUserLogin;
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyCustomClassWithOldPropertyCalls
{
    /**
     * @var TypoScriptFrontendController
     */
    private $typoScriptFrontendController;

    public function __construct()
    {
        $this->typoScriptFrontendController = $GLOBALS['TSFE'];
    }

    public function myMethod(): void
    {
        $frontendUserIsLoggedIn = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('frontend.user', 'isLoggedIn');
        $backendUserIsLoggedIn = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('backend.user', 'isLoggedIn');
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
