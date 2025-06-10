<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerReadOnlyPropertiesRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyClass extends ActionController
{
    public function myAction(): void
    {
        $id = $GLOBALS['TSFE']->id;
        $rootLine = $GLOBALS['TSFE']->rootLine;
        $page = $GLOBALS['TSFE']->page;
        $contentPid = $GLOBALS['TSFE']->contentPid;
    }

    public function myActionWithTypoScriptFrontendController(): void
    {
        $id = $this->getTypoScriptFrontendController()->id;
        $rootLine = $this->getTypoScriptFrontendController()->rootLine;
        $page = $this->getTypoScriptFrontendController()->page;
        $contentPid = $this->getTypoScriptFrontendController()->contentPid;
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerReadOnlyPropertiesRector\Fixture;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyClass extends ActionController
{
    public function myAction(): void
    {
        $id = $this->request->getAttribute('frontend.page.information')->getId();
        $rootLine = $this->request->getAttribute('frontend.page.information')->getRootLine();
        $page = $this->request->getAttribute('frontend.page.information')->getPageRecord();
        $contentPid = $this->request->getAttribute('frontend.page.information')->getContentFromPid();
    }

    public function myActionWithTypoScriptFrontendController(): void
    {
        $id = $this->request->getAttribute('frontend.page.information')->getId();
        $rootLine = $this->request->getAttribute('frontend.page.information')->getRootLine();
        $page = $this->request->getAttribute('frontend.page.information')->getPageRecord();
        $contentPid = $this->request->getAttribute('frontend.page.information')->getContentFromPid();
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
