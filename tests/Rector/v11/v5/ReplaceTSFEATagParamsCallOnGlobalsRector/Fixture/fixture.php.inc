<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\ReplaceTSFEATagParamsCallOnGlobalsRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class TestClass
{
    protected function testWithGlobals(): void
    {
        $foo = $GLOBALS['TSFE']->ATagParams;
    }

    protected function testWithReference(): void
    {
        $ATagParams = $this->getTypoScriptFrontendController()->ATagParams;
    }

    public function doNotTouchThis(): void
    {
        $type3 = $GLOBALS['TYPO3_REQUEST']->ATagParams;
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v5\ReplaceTSFEATagParamsCallOnGlobalsRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class TestClass
{
    protected function testWithGlobals(): void
    {
        $foo = $GLOBALS['TSFE']->config['config']['ATagParams'] ?? '';
    }

    protected function testWithReference(): void
    {
        $ATagParams = $this->getTypoScriptFrontendController()->config['config']['ATagParams'] ?? '';
    }

    public function doNotTouchThis(): void
    {
        $type3 = $GLOBALS['TYPO3_REQUEST']->ATagParams;
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
?>
