<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseContextApiRector\Fixture;

use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyClass
{
    private static function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }

    public function autologinFrontendUser(FrontendUser $frontendUser): void
    {
        $tsfe = self::getTypoScriptFrontendController();
        $tsfe->fe_user->checkPid = false;
        $tsfe->fe_user->forceSetCookie = true;
        $tsfe->loginUser = true;
        $isUserLoggedIn = $tsfe->loginUser;
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseContextApiRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyClass
{
    private static function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }

    public function autologinFrontendUser(FrontendUser $frontendUser): void
    {
        $tsfe = self::getTypoScriptFrontendController();
        $tsfe->fe_user->checkPid = false;
        $tsfe->fe_user->forceSetCookie = true;
        $tsfe->loginUser = true;
        $isUserLoggedIn = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('frontend.user', 'isLoggedIn');
    }
}

?>
