<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\UseConfigArrayForTSFEPropertiesRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyController
{
    public function testWithGlobals(): void
    {
        $fileTarget = $GLOBALS['TSFE']->fileTarget;
    }

    public function testWithReference(): void
    {
        $intTarget = $this->getTypoScriptFrontendController()->intTarget;
        $extTarget = $this->getTypoScriptFrontendController()->extTarget;
        $spamProtectEmailAddresses = $this->getTypoScriptFrontendController()->spamProtectEmailAddresses;
        $baseUrl = $this->getTypoScriptFrontendController()->baseUrl;
    }

    public function doNotTouchThis(): void
    {
        $foo = $GLOBALS['TSFE']->foo;
        $fileTarget = $GLOBALS['TYPO3_REQUEST']->fileTarget;
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\UseConfigArrayForTSFEPropertiesRector\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class MyController
{
    public function testWithGlobals(): void
    {
        $fileTarget = $GLOBALS['TSFE']->config['config']['fileTarget'];
    }

    public function testWithReference(): void
    {
        $intTarget = $this->getTypoScriptFrontendController()->config['config']['intTarget'];
        $extTarget = $this->getTypoScriptFrontendController()->config['config']['extTarget'];
        $spamProtectEmailAddresses = $this->getTypoScriptFrontendController()->config['config']['spamProtectEmailAddresses'];
        $baseUrl = $this->getTypoScriptFrontendController()->config['config']['baseUrl'];
    }

    public function doNotTouchThis(): void
    {
        $foo = $GLOBALS['TSFE']->foo;
        $fileTarget = $GLOBALS['TYPO3_REQUEST']->fileTarget;
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
