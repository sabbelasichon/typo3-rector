<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerMethodCallsRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyClass extends ActionController
{
    public function myAction(): void
    {
        $requestedId = $GLOBALS['TSFE']->getRequestedId();
        $language = $GLOBALS['TSFE']->getLanguage();
        $site = $GLOBALS['TSFE']->getSite();
        $pageArguments = $GLOBALS['TSFE']->getPageArguments();
    }

    public function myActionWithTypoScriptFrontendController(): void
    {
        $requestedId = $this->getTypoScriptFrontendController()->getRequestedId();
        $language = $this->getTypoScriptFrontendController()->getLanguage();
        $site = $this->getTypoScriptFrontendController()->getSite();
        $pageArguments = $this->getTypoScriptFrontendController()->getPageArguments();
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerMethodCallsRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyClass extends ActionController
{
    public function myAction(): void
    {
        $requestedId = $this->request->getAttribute('routing')->getPageId();
        $language = $this->request->getAttribute('site')->getDefaultLanguage();
        $site = $this->request->getAttribute('site');
        $pageArguments = $this->request->getAttribute('routing');
    }

    public function myActionWithTypoScriptFrontendController(): void
    {
        $requestedId = $this->request->getAttribute('routing')->getPageId();
        $language = $this->request->getAttribute('site')->getDefaultLanguage();
        $site = $this->request->getAttribute('site');
        $pageArguments = $this->request->getAttribute('routing');
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
