<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveTSFEMetaCharSetCallsRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class TestClass
{
    public function testWithGlobals(): void
    {
        $foo = $GLOBALS['TSFE']->metaCharset;
    }

    public function testWithReference(): void
    {
        $bar = $this->getTypoScriptFrontendController()->metaCharset;
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveTSFEMetaCharSetCallsRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class TestClass
{
    public function testWithGlobals(): void
    {
        $foo = 'utf-8';
    }

    public function testWithReference(): void
    {
        $bar = 'utf-8';
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
