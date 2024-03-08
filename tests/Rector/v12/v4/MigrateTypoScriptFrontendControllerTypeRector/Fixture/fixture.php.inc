<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\MigrateTypoScriptFrontendControllerTypeRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class TestClass
{
    protected function getTypeWithGlobals()
    {
        $type = $GLOBALS['TSFE']->type;
        $type2 = $GLOBALS['TSFE']->getPageArguments()->getPageType();
    }

    protected function getTypeWithLocalVariable()
    {
        $tsfe = $this->getTypoScriptFrontendController();
        $type = $tsfe->type;
    }

    public function doNotTouchThis()
    {
        $type3 = $GLOBALS['TYPO3_REQUEST']->type;
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v4\MigrateTypoScriptFrontendControllerTypeRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class TestClass
{
    protected function getTypeWithGlobals()
    {
        $type = $GLOBALS['TSFE']->getPageArguments()->getPageType();
        $type2 = $GLOBALS['TSFE']->getPageArguments()->getPageType();
    }

    protected function getTypeWithLocalVariable()
    {
        $tsfe = $this->getTypoScriptFrontendController();
        $type = $tsfe->getPageArguments()->getPageType();
    }

    public function doNotTouchThis()
    {
        $type3 = $GLOBALS['TYPO3_REQUEST']->type;
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
?>
