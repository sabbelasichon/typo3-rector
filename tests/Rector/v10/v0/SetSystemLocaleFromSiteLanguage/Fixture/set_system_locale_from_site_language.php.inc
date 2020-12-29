<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class ExtbaseBridge
{
    public function __construct()
    {
        $this->bootFrontend();
        $this->initLanguage();
    }

    protected function bootFrontend(): void
    {
        $GLOBALS['TSFE']->fetch_the_id();
        $GLOBALS['TSFE']->getFromCache();
        $GLOBALS['TSFE']->getConfigArray();
        $GLOBALS['TSFE']->settingLanguage();
        $GLOBALS['TSFE']->settingLocale();
        $GLOBALS['TSFE']->newCObj();

        $controller = GeneralUtility::makeInstance(TypoScriptFrontendController::class, null, 0, 0);
        $controller->settingLocale();
    }

    protected function initLanguage(): void
    {
        $controller = $this->getTypoScriptFrontendController();
        $controller->settingLanguage();
        $controller->settingLocale();
    }

    /**
     * @return TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
-----
<?php

use TYPO3\CMS\Core\Localization\Locales;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class ExtbaseBridge
{
    public function __construct()
    {
        $this->bootFrontend();
        $this->initLanguage();
    }

    protected function bootFrontend(): void
    {
        $GLOBALS['TSFE']->fetch_the_id();
        $GLOBALS['TSFE']->getFromCache();
        $GLOBALS['TSFE']->getConfigArray();
        $GLOBALS['TSFE']->settingLanguage();
        Locales::setSystemLocaleFromSiteLanguage($GLOBALS['TSFE']->getLanguage());
        $GLOBALS['TSFE']->newCObj();

        $controller = GeneralUtility::makeInstance(TypoScriptFrontendController::class, null, 0, 0);
        Locales::setSystemLocaleFromSiteLanguage($controller->getLanguage());
    }

    protected function initLanguage(): void
    {
        $controller = $this->getTypoScriptFrontendController();
        $controller->settingLanguage();
        Locales::setSystemLocaleFromSiteLanguage($controller->getLanguage());
    }

    /**
     * @return TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
