<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v0\UseTwoLetterIsoCodeFromSiteLanguageRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class TestClass
{
    public function testWithGlobals(): void
    {
        if ($GLOBALS['TSFE']->sys_language_isocode) {
            $GLOBALS['LANG']->init($GLOBALS['TSFE']->sys_language_isocode);
        }

        $userObject = $GLOBALS['TSFE']->fe_user->user;
    }

    public function testWithReference(): void
    {
        $tsfe = $this->getTypoScriptFrontendController();
        if ($tsfe->sys_language_isocode) {
            $GLOBALS['LANG']->init($tsfe->sys_language_isocode);
        }

        $userObject = $tsfe->fe_user->user;
    }

    public function testWithChain(): void
    {
        if ($this->getTypoScriptFrontendController()->sys_language_isocode) {
            $GLOBALS['LANG']->init($this->getTypoScriptFrontendController()->sys_language_isocode);
        }

        $userObject = $this->getTypoScriptFrontendController()->fe_user->user;
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v0\UseTwoLetterIsoCodeFromSiteLanguageRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class TestClass
{
    public function testWithGlobals(): void
    {
        if ($GLOBALS['TSFE']->getLanguage()->getTwoLetterIsoCode()) {
            $GLOBALS['LANG']->init($GLOBALS['TSFE']->getLanguage()->getTwoLetterIsoCode());
        }

        $userObject = $GLOBALS['TSFE']->fe_user->user;
    }

    public function testWithReference(): void
    {
        $tsfe = $this->getTypoScriptFrontendController();
        if ($tsfe->getLanguage()->getTwoLetterIsoCode()) {
            $GLOBALS['LANG']->init($tsfe->getLanguage()->getTwoLetterIsoCode());
        }

        $userObject = $tsfe->fe_user->user;
    }

    public function testWithChain(): void
    {
        if ($this->getTypoScriptFrontendController()->getLanguage()->getTwoLetterIsoCode()) {
            $GLOBALS['LANG']->init($this->getTypoScriptFrontendController()->getLanguage()->getTwoLetterIsoCode());
        }

        $userObject = $this->getTypoScriptFrontendController()->fe_user->user;
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
?>
